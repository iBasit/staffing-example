<?php
namespace AppBundle\Utils;


use AppBundle\Entity\StaffSlot;

class Work
{
    protected $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    /**
     * Group shift of the staff by staff and day
     *
     * @param $staffSlots StaffSlot[]
     *
     * @return array
     */
    public function shiftGroupByStaff ($staffSlots)
    {
        $group = [];

        foreach ($staffSlots as $slot)
        {
            $day = $this->days["{$slot->getDayNumber()}"]; // just for preference

            $startTime = $slot->getStartTime();
            $endTime   = $slot->getEndTime();

            if ($endTime < $startTime)
                $endTime->modify('+1 day');

            $diff = $endTime->diff($startTime);

            $group["{$slot->getStaff()}"]["{$day}"] = [
                'staff' => $slot->getStaff(),
                'startTime' => $startTime,
                'endTime' => $endTime,
                'workHours' => ($diff->format('%i') > 0) ? $diff->format('%h:%i') : $diff->format('%h'),
                'workAloneMinutes' => 0,
            ];
        }


        $this->calculateAllAloneMinutes($group);

        return $group;
    }

    /**
     * calculate all the alone minutes and add to the group
     * @param $groupByShifts
     */
    public function calculateAllAloneMinutes (&$groupByShifts)
    {
        // check each day shift
        foreach ($this->days as $day)
        {
            $shiftStaff = $this->getDayShiftStaffTimes($groupByShifts, $day);

            $stuffAloneMinutes = [];
            foreach ($shiftStaff as $id => $employee) {
                $colleague_shifts = array_diff_key($shiftStaff, [$id => '']);  // build new array and exclude current staff member

                if (in_array($employee, $colleague_shifts)) {  // check for same start and end times elsewhere
                    $stuffAloneMinutes[$id] = 0;  // exact duplicate allows shortcut as "never alone"
                } else {
                    $stuffAloneMinutes[$id] = $this->getStaffAloneTime($colleague_shifts, [$employee]);  // tear down times where target employee is alone
                }
            }

            $this->assignAloneMinutes($groupByShifts, $stuffAloneMinutes, $day);
        }
    }

    /**
     * build new array for all the working employees shift for the selected day
     *
     * @param array $groupByShifts
     * @param $day
     * @return array
     */
    public function getDayShiftStaffTimes (Array $groupByShifts, $day)
    {
        $shiftDay = [];

        foreach ($groupByShifts as $id => $values)
        {
            if (isset($groupByShifts[$id][$day]))
            {
                $beginningDay = clone $groupByShifts[$id][$day]['startTime'];
                $beginningDay->modify('midnight');
                $shiftDay[$id] = [
                    abs(($beginningDay->getTimestamp() - $groupByShifts[$id][$day]['startTime']->getTimestamp()) / 60),
                    abs(($beginningDay->getTimestamp() - $groupByShifts[$id][$day]['endTime']->getTimestamp()) / 60)

                ];
            }
        }

        return $shiftDay;
    }

    /**
     * get single staff alone minutes after checking with colleague shift times
     *
     * @param array $colleague_shifts
     * @param array $excludeColleague_shifts normally just [$currentEmployee]
     * @return float|int
     */
    public function getStaffAloneTime (Array $colleague_shifts, Array $excludeColleague_shifts)
    {
        // initially, PoS is only one element
        foreach ($colleague_shifts as $colleagueEmployeeId => $colleagueEmployeeTimes) {

            foreach ($excludeColleague_shifts as $excludeEmployeeId => $excludeEmployeeTimes) {

                if ($colleagueEmployeeTimes[0] <= $excludeEmployeeTimes[0] && $colleagueEmployeeTimes[1] >= $excludeEmployeeTimes[1]) {
                    unset($excludeColleague_shifts[$excludeEmployeeId]);
                    continue;  // fully covered by coworker
                }

                $temp = [];
                if ($excludeEmployeeTimes[0] < $colleagueEmployeeTimes[0] && $colleagueEmployeeTimes[0] < $excludeEmployeeTimes[1]) {
                    $temp[] = [$excludeEmployeeTimes[0], $colleagueEmployeeTimes[0]];    // push new unmatched start into temp PoS array
                }

                if ($excludeEmployeeTimes[1] > $colleagueEmployeeTimes[1] && $colleagueEmployeeTimes[1] > $excludeEmployeeTimes[0]) {
                    $temp[] = [$colleagueEmployeeTimes[1], $excludeEmployeeTimes[1]];    // push new unmatched end into temp PoS array
                }

                if ($temp) {
                    array_splice($excludeColleague_shifts, $excludeEmployeeId, 1, $temp);  // replace the current PoS with 1 or 2 new PoS subarrays
                }
            }
            if (!$excludeColleague_shifts) {
                return 0;  // no minutes alone
            }
        }

        // subtract all end alone minutes from all start alone minutes
        return array_sum(array_column($excludeColleague_shifts, 1)) - array_sum(array_column($excludeColleague_shifts, 0));
    }

    /**
     * assign alone minutes back to the group
     *
     * @param array $groupByShifts
     * @param array $aloneTimes
     * @param $day
     */
    protected function assignAloneMinutes (Array &$groupByShifts, Array $aloneTimes, $day)
    {
        if (empty($aloneTimes))
            return;

        foreach ($aloneTimes as $id => $minutes)
        {
            $groupByShifts[$id][$day]['workAloneMinutes'] = $minutes;
        }
    }
}