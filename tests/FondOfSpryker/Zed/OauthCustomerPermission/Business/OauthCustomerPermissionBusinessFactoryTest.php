<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface;
use FondOfSpryker\Zed\OauthCustomerPermission\OauthCustomerPermissionDependencyProvider;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class OauthCustomerPermissionBusinessFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionBusinessFactory
     */
    protected $oauthCustomerPermissionBusinessFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected $permissionFacadeInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected $companyUserFacadeInterfaceMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionFacadeInterfaceMock = $this->getMockBuilder(PermissionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserFacadeInterfaceMock = $this->getMockBuilder(CompanyUserFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->oauthCustomerPermissionBusinessFactory = new OauthCustomerPermissionBusinessFactory();
        $this->oauthCustomerPermissionBusinessFactory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testCreateCompanyUsersCustomerIdentifierExpander(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->withConsecutive(
                [OauthCustomerPermissionDependencyProvider::FACADE_PERMISSION],
                [OauthCustomerPermissionDependencyProvider::FACADE_COMPANY_USER]
            )->willReturnOnConsecutiveCalls(
                $this->permissionFacadeInterfaceMock,
                $this->companyUserFacadeInterfaceMock
            );

        $this->assertInstanceOf(
            CompanyUsersCustomerIdentifierExpanderInterface::class,
            $this->oauthCustomerPermissionBusinessFactory->createCompanyUsersCustomerIdentifierExpander()
        );
    }
}
