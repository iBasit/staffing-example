<?php
namespace AppBundle\Repository;

use AppBundle\Entity\StaffSlot;
use Doctrine\ORM\EntityRepository;

class StaffSlotRepository extends EntityRepository
{
    /**
     * Get staff shifts who are working only (not day off)
     *
     * @return StaffSlot[]
     */
    public function workingShifts ()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT s
                FROM  AppBundle:StaffSlot s
                WHERE s.slotType = :shiftType
                AND   s.staff IS NOT NULL
                ORDER BY s.staff
                '
            )
            ->setParameter('shiftType', 'shift')
            ->getResult();
    }
}