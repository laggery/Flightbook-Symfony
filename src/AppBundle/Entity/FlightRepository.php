<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * FlightRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FlightRepository extends EntityRepository {

    public function isGliderUsed($userId, $gliderId) {
        $nb = $this->createQueryBuilder('f')
                ->select('COUNT(f)')
                ->where("f.user =" . $userId)
                ->andWhere("f.glider =" . $gliderId)
                ->getQuery()
                ->getSingleScalarResult();
        return $nb > 0 ? true : false;
    }
    
    public function isPlaceUsed($userId, $placeId) {
        $nb = $this->createQueryBuilder('f')
                ->select('COUNT(f)')
                ->where("f.user =" . $userId)
                ->andWhere("f.start =" . $placeId)
                ->orWhere("f.landing =" . $placeId)
                ->getQuery()
                ->getSingleScalarResult();
        return $nb > 0 ? true : false;
    }

    public function getLastFlight($userId) {
        try {
            return $this->createQueryBuilder('f')
                ->where('f.user =' . $userId)
                ->orderBy('f.timestamp', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }
    }
}
