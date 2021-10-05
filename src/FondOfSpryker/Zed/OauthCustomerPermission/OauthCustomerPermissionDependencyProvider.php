<?php

declare(strict_types = 1);

namespace FondOfSpryker\Zed\OauthCustomerPermission;

use Orm\Zed\Permission\Persistence\Base\SpyPermissionQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class OauthCustomerPermissionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PERMISSION = 'PROPEL_QUERY_PERMISSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        return $this->addPermissionQuery($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PERMISSION] = static function () {
            return SpyPermissionQuery::create();
        };

        return $container;
    }
}
