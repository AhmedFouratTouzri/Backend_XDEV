<?php

namespace AppBundle\Repository;

/**
 * EmplacementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmplacementRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLastAddedListings()
    {
        $rawSql = "SELECT * FROM emplacement ORDER BY id DESC LIMIT 6";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);

        return $stmt->fetchAll();
    }
}
