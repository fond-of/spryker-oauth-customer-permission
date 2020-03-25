<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Communication\Plugin\OauthCustomerConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionFacade;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CompanyUsersCustomerIdentifierExpanderPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Communication\Plugin\OauthCustomerConnector\CompanyUsersCustomerIdentifierExpanderPlugin
     */
    protected $companyUsersCustomerIdentifierExpanderPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionFacade
     */
    protected $oauthCustomerPermissionFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    protected $customerIdentifierTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->oauthCustomerPermissionFacadeMock = $this->getMockBuilder(OauthCustomerPermissionFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerIdentifierTransferMock = $this->getMockBuilder(CustomerIdentifierTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUsersCustomerIdentifierExpanderPlugin = new CompanyUsersCustomerIdentifierExpanderPlugin();
        $this->companyUsersCustomerIdentifierExpanderPlugin->setFacade($this->oauthCustomerPermissionFacadeMock);
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifier(): void
    {
        $this->oauthCustomerPermissionFacadeMock->expects($this->atLeastOnce())
            ->method('expandCustomerIdentifierWithCompanyUsersPermissions')
            ->with($this->customerIdentifierTransferMock, $this->customerTransferMock)
            ->willReturn($this->customerIdentifierTransferMock);

        $this->assertInstanceOf(
            CustomerIdentifierTransfer::class,
            $this->companyUsersCustomerIdentifierExpanderPlugin->expandCustomerIdentifier(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }
}
