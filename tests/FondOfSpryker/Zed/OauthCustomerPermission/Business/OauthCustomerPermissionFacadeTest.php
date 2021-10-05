<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class OauthCustomerPermissionFacadeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionFacade
     */
    protected $oauthCustomerPermissionFacade;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    protected $customerIdentifierTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionBusinessFactory
     */
    protected $oauthCustomerPermissionBusinessFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface
     */
    protected $companyUsersCustomerIdentifierExpanderMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->oauthCustomerPermissionBusinessFactoryMock = $this->getMockBuilder(OauthCustomerPermissionBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerIdentifierTransferMock = $this->getMockBuilder(CustomerIdentifierTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUsersCustomerIdentifierExpanderMock = $this->getMockBuilder(CompanyUsersCustomerIdentifierExpanderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->oauthCustomerPermissionFacade = new OauthCustomerPermissionFacade();
        $this->oauthCustomerPermissionFacade->setFactory($this->oauthCustomerPermissionBusinessFactoryMock);
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWithCompanyUsersPermissions(): void
    {
        $this->oauthCustomerPermissionBusinessFactoryMock->expects(static::atLeastOnce())
            ->method('createCompanyUsersCustomerIdentifierExpander')
            ->willReturn($this->companyUsersCustomerIdentifierExpanderMock);

        $this->companyUsersCustomerIdentifierExpanderMock->expects(static::atLeastOnce())
            ->method('expandCustomerIdentifierWithPermissions')
            ->with($this->customerIdentifierTransferMock, $this->customerTransferMock)
            ->willReturn($this->customerIdentifierTransferMock);

        static::assertEquals(
            $this->customerIdentifierTransferMock,
            $this->oauthCustomerPermissionFacade->expandCustomerIdentifierWithCompanyUsersPermissions(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }
}
