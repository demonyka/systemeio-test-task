<?php

namespace App\Repository;

use App\Entity\TaxRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaxRate>
 *
 * @method TaxRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxRate[]    findAll()
 * @method TaxRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxRate::class);
    }

    /**
     * Finds the tax rate by tax number.
     *
     * @param string $taxNumber
     * @return TaxRate|null
     */
    public function findRateByTaxNumber(string $taxNumber): ?TaxRate
    {
        $countryCode = substr($taxNumber, 0, 2);
        $isValid = $this->validateTaxNumber($countryCode, $taxNumber);

        if (!$isValid) {
            return null;
        }

        try {
            return $this->createQueryBuilder('t')
                ->where('t.countryCode = :countryCode')
                ->setParameter('countryCode', $countryCode)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Validates the tax number based on the country code.
     *
     * @param string $countryCode
     * @param string $taxNumber
     * @return bool
     */
    private function validateTaxNumber(string $countryCode, string $taxNumber): bool
    {
        return match ($countryCode) {
            'DE' => preg_match('/^DE\d{9}$/', $taxNumber) === 1,
            'IT' => preg_match('/^IT\d{11}$/', $taxNumber) === 1,
            'GR' => preg_match('/^GR\d{9}$/', $taxNumber) === 1,
            'FR' => preg_match('/^FR[A-Z0-9]{2}\d{9}$/', $taxNumber) === 1,
            default => false,
        };
    }
}