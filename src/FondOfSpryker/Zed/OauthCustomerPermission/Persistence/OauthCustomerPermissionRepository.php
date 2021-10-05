<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Persistence;

use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionPersistenceFactory getFactory()
 */
class OauthCustomerPermissionRepository extends AbstractRepository implements OauthCustomerPermissionRepositoryInterface
{
    public const VIRTUAL_COL_FK_COMPANY = 'fk_company';

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    public function findPermissionsByIdCustomer(int $idCustomer): array
    {
        $entityCollection = $this->getFactory()
            ->getPermissionQuery()
            ->useSpyCompanyRoleToPermissionQuery()
                ->useCompanyRoleQuery()
                    ->useSpyCompanyRoleToCompanyUserQuery()
                        ->useCompanyUserQuery()
                            ->filterByFkCustomer($idCustomer)
                            ->withColumn(SpyCompanyUserTableMap::COL_FK_COMPANY, static::VIRTUAL_COL_FK_COMPANY)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createPermissionMapper()
            ->mapEntityCollectionToTransfers($entityCollection);
    }
}
