<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\Permission\Persistence\Base\SpyPermission;
use Propel\Runtime\Collection\ObjectCollection;

interface PermissionMapperInterface
{
    /**
     * @param \Orm\Zed\Permission\Persistence\Base\SpyPermission $entity
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function mapEntityToTransfer(SpyPermission $entity): PermissionTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Permission\Persistence\Base\SpyPermission[] $entityCollection
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    public function mapEntityCollectionToTransfers(ObjectCollection $entityCollection): array;
}
