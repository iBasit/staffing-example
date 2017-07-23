<?php


namespace AppBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WorkTest extends WebTestCase
{
    public function testGetDayShiftStaffTimesNonExistingDay ()
    {
        $work = $this->getWork();

        $groupsByShift = $this->getMockGroupByShifts();
        $tuesdayShift = $work->getDayShiftStaffTimes($groupsByShift, 'tuesday');

        $this->assertCount(0, $tuesdayShift);
    }

    public function testGetDayShiftStaffTimesArrayCount ()
    {
        $work = $this->getWork();

        $groupsByShift = $this->getMockGroupByShifts();
        $mondayShift = $work->getDayShiftStaffTimes($groupsByShift, 'monday');

        $this->assertCount(4, $mondayShift);
    }

    public function testGetDayShiftStaffTimesKeysCheck ()
    {
        $work = $this->getWork();

        $groupsByShift = $this->getMockGroupByShifts();
        $mondayShift = $work->getDayShiftStaffTimes($groupsByShift, 'monday');

        $this->assertCount(2, $mondayShift['1']);
    }

    public function testGetDayShiftStaffTimesValuesCheck ()
    {
        $work = $this->getWork();

        $groupsByShift = $this->getMockGroupByShifts();
        $mondayShift = $work->getDayShiftStaffTimes($groupsByShift, 'monday');

        $this->assertEquals(660, $mondayShift['1']['0']);
        $this->assertEquals(1140, $mondayShift['1']['1']);
    }


    public function testStaffAloneTimeMatchResult ()
    {
        $work = $this->getWork();

        $groupsByShift = $this->getMockGroupByShifts();
        $mondayShift = $work->getDayShiftStaffTimes($groupsByShift, 'monday');

        $currentEmployee = $mondayShift['1'];
        $colleagues = $mondayShift;
        unset($colleagues['1']); // all others

        $this->assertEquals(30, $work->getStaffAloneTime($colleagues, [$currentEmployee]));
    }

    /**
     * @return array|mixed
     */
    protected function getMockGroupByShifts ()
    {
        $groupByShifts = [];
        $groupByShifts[1]['sunday'] = ['staff' => '1', 'startTime' => (new \DateTime())->modify('2017-02-21 11:00:00'), 'endTime' => (new \DateTime())->modify('2017-02-21 19:00:00'), 'workHours' => 0, 'workAloneMinutes' => 0];
        $groupByShifts[1]['monday'] = ['staff' => '1', 'startTime' => (new \DateTime())->modify('2017-02-22 11:00:00'), 'endTime' => (new \DateTime())->modify('2017-02-22 19:00:00'), 'workHours' => 0, 'workAloneMinutes' => 0];
        $groupByShifts[2]['monday'] = ['staff' => '2', 'startTime' => (new \DateTime())->modify('2017-02-22 11:30:00'), 'endTime' => (new \DateTime())->modify('2017-02-22 19:00:00'), 'workHours' => 0, 'workAloneMinutes' => 0];
        $groupByShifts[3]['monday'] = ['staff' => '3', 'startTime' => (new \DateTime())->modify('2017-02-22 19:00:00'), 'endTime' => (new \DateTime())->modify('2017-02-23 02:00:00'), 'workHours' => 0, 'workAloneMinutes' => 0];
        $groupByShifts[4]['monday'] = ['staff' => '4', 'startTime' => (new \DateTime())->modify('2017-02-22 19:00:00'), 'endTime' => (new \DateTime())->modify('2017-02-23 03:00:00'), 'workHours' => 0, 'workAloneMinutes' => 0];

        return $groupByShifts;
    }

    /**
     * @return Work
     */
    protected function getWork ()
    {
        $client = self::createClient();
        return $client->getContainer()->get('app.work');
    }

}
