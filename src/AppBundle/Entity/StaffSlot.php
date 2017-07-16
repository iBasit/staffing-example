<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RotaSlotStaff
 *
 * @ORM\Table(name="rota_slot_staff", indexes={@ORM\Index(name="rotaid", columns={"rotaid", "staffid"}), @ORM\Index(name="daynumber", columns={"daynumber"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StaffSlotRepository")
 */
class StaffSlot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="rotaid", type="integer", nullable=false)
     */
    private $rota;

    /**
     * @var boolean
     *
     * @ORM\Column(name="daynumber", type="boolean", nullable=false)
     */
    private $dayNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="staffid", type="integer", nullable=true)
     */
    private $staff;

    /**
     * @var string
     *
     * @ORM\Column(name="slottype", type="string", length=20, nullable=false)
     */
    private $slotType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starttime", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endtime", type="time", nullable=true)
     */
    private $endTime;

    /**
     * @var float
     *
     * @ORM\Column(name="workhours", type="float", precision=4, scale=2, nullable=false)
     */
    private $workHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="premiumminutes", type="integer", nullable=true)
     */
    private $premiumMinutes;

    /**
     * @var integer
     *
     * @ORM\Column(name="roletypeid", type="integer", nullable=true)
     */
    private $roleType;

    /**
     * @var integer
     *
     * @ORM\Column(name="freeminutes", type="integer", nullable=true)
     */
    private $freeMinutes;

    /**
     * @var integer
     *
     * @ORM\Column(name="seniorcashierminutes", type="integer", nullable=true)
     */
    private $seniorCashierMinutes;

    /**
     * @var string
     *
     * @ORM\Column(name="splitshifttimes", type="string", length=11, nullable=true)
     */
    private $splitShiftTimes;

    /**
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRota ()
    {
        return $this->rota;
    }

    /**
     * @param int $rota
     */
    public function setRota ($rota)
    {
        $this->rota = $rota;
    }

    /**
     * @return boolean
     */
    public function isDayNumber ()
    {
        return $this->dayNumber;
    }

    /**
     * @param boolean $dayNumber
     */
    public function setDayNumber ($dayNumber)
    {
        $this->dayNumber = $dayNumber;
    }

    /**
     * @return int
     */
    public function getStaff ()
    {
        return $this->staff;
    }

    /**
     * @param int $staff
     */
    public function setStaff ($staff)
    {
        $this->staff = $staff;
    }

    /**
     * @return string
     */
    public function getSlotType ()
    {
        return $this->slotType;
    }

    /**
     * @param string $slotType
     */
    public function setSlotType ($slotType)
    {
        $this->slotType = $slotType;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime ()
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $startTime
     */
    public function setStartTime ($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime ()
    {
        return $this->endTime;
    }

    /**
     * @param \DateTime $endTime
     */
    public function setEndTime ($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return float
     */
    public function getWorkHours ()
    {
        return $this->workHours;
    }

    /**
     * @param float $workHours
     */
    public function setWorkHours ($workHours)
    {
        $this->workHours = $workHours;
    }

    /**
     * @return int
     */
    public function getPremiumMinutes ()
    {
        return $this->premiumMinutes;
    }

    /**
     * @param int $premiumMinutes
     */
    public function setPremiumMinutes ($premiumMinutes)
    {
        $this->premiumMinutes = $premiumMinutes;
    }

    /**
     * @return int
     */
    public function getRoleType ()
    {
        return $this->roleType;
    }

    /**
     * @param int $roleType
     */
    public function setRoleType ($roleType)
    {
        $this->roleType = $roleType;
    }

    /**
     * @return int
     */
    public function getFreeMinutes ()
    {
        return $this->freeMinutes;
    }

    /**
     * @param int $freeMinutes
     */
    public function setFreeMinutes ($freeMinutes)
    {
        $this->freeMinutes = $freeMinutes;
    }

    /**
     * @return int
     */
    public function getSeniorCashierMinutes ()
    {
        return $this->seniorCashierMinutes;
    }

    /**
     * @param int $seniorCashierMinutes
     */
    public function setSeniorCashierMinutes ($seniorCashierMinutes)
    {
        $this->seniorCashierMinutes = $seniorCashierMinutes;
    }

    /**
     * @return string
     */
    public function getSplitShiftTimes ()
    {
        return $this->splitShiftTimes;
    }

    /**
     * @param string $splitShiftTimes
     */
    public function setSplitShiftTimes ($splitShiftTimes)
    {
        $this->splitShiftTimes = $splitShiftTimes;
    }
}

