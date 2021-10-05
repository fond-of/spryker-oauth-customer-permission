<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpander;
use FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepository;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class OauthCustomerPermissionBusinessFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionBusinessFactory
     */
    protected $oauthCustomerPermissionBusinessFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepository
     */
    protected $repositoryMock;

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
        $this->repositoryMock = $this->getMockBuilder(OauthCustomerPermissionRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionFacadeInterfaceMock = $this->getMockBuilder(PermissionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserFacadeInterfaceMock = $this->getMockBuilder(CompanyUserFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->oauthCustomerPermissionBusinessFactory = new OauthCustomerPermissionBusinessFactory();
        $this->oauthCustomerPermissionBusinessFactory->setRepository($this->repositoryMock);
    }

    /**
     * @return void
     */
    public function testCreateCompanyUsersCustomerIdentifierExpander(): void
    {
        $this->assertInstanceOf(
            CompanyUsersCustomerIdentifierExpander::class,
            $this->oauthCustomerPermissionBusinessFactory->createCompanyUsersCustomerIdentifierExpander()
        );
    }
}
