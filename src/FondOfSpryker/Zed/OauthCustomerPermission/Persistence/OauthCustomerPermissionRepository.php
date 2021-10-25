<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Persistence;

use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionPersistenceFactory getFactory()
 */
class OauthCustomerPermissionRepository extends AbstractRepository implements OauthCustomerPermissionRepositoryInterface
{
    public const VIRTUAL_COL_COMPANY_IDS = 'company_ids';

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    public function findPermissionsByIdCustomer(int $idCustomer): array
    {
        $withColumnClause = sprintf(
            'GROUP_CONCAT(%s ORDER BY %s)',
            SpyCompanyUserTableMap::COL_FK_COMPANY,
            SpyCompanyUserTableMap::COL_FK_COMPANY
        );

        $entityCollection = $this->getFactory()
            ->getPermissionQuery()
            ->useSpyCompanyRoleToPermissionQuery()
                ->useCompanyRoleQuery()
                    ->useSpyCompanyRoleToCompanyUserQuery()
                        ->useCompanyUserQuery()
                            ->filterByFkCustomer($idCustomer)
                            ->filterByIsActive(true)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn($withColumnClause, static::VIRTUAL_COL_COMPANY_IDS)
            ->groupByIdPermission()
            ->find();

        return $this->getFactory()
            ->createPermissionMapper()
            ->mapEntityCollectionToTransfers($entityCollection);
    }
}
