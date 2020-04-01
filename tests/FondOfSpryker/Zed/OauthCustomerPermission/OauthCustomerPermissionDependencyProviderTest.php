<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Container;

class OauthCustomerPermissionDependencyProviderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\OauthCustomerPermissionDependencyProvider
     */
    protected $oauthCustomerPermissionDependencyProvider;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->oauthCustomerPermissionDependencyProvider = new OauthCustomerPermissionDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependencies(): void
    {
        $this->assertInstanceOf(
            Container::class,
            $this->oauthCustomerPermissionDependencyProvider->provideBusinessLayerDependencies(
                $this->containerMock
            )
        );
    }
}
