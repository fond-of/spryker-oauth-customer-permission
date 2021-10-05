<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Persistence;

interface OauthCustomerPermissionRepositoryInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    public function findPermissionsByIdCustomer(int $idCustomer): array;
}
