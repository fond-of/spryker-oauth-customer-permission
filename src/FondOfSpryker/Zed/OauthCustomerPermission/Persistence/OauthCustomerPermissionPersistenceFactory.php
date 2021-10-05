<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Persistence;

use FondOfSpryker\Zed\OauthCustomerPermission\OauthCustomerPermissionDependencyProvider;
use FondOfSpryker\Zed\OauthCustomerPermission\Persistence\Propel\Mapper\PermissionMapper;
use FondOfSpryker\Zed\OauthCustomerPermission\Persistence\Propel\Mapper\PermissionMapperInterface;
use Orm\Zed\Permission\Persistence\Base\SpyPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepositoryInterface getRepository()
 */
class OauthCustomerPermissionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Permission\Persistence\Base\SpyPermissionQuery
     */
    public function getPermissionQuery(): SpyPermissionQuery
    {
        return $this->getProvidedDependency(OauthCustomerPermissionDependencyProvider::PROPEL_QUERY_PERMISSION);
    }

    /**
     * @return \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\Propel\Mapper\PermissionMapperInterface
     */
    public function createPermissionMapper(): PermissionMapperInterface
    {
        return new PermissionMapper();
    }
}
