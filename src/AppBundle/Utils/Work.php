<?php
namespace AppBundle\Utils;


use AppBundle\Entity\StaffSlot;

class Work
{
    const DAYS = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

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
            $day = self::DAYS["{$slot->getDayNumber()}"]; // just for preference

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

        return $group;
    }
}