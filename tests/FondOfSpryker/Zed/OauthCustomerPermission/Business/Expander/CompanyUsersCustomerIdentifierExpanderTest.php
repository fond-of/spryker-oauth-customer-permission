<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class CompanyUsersCustomerIdentifierExpanderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpander
     */
    protected $companyUsersCustomerIdentifierExpander;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected $permissionFacadeInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected $companyUserFacadeInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    protected $customerIdentifierTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected $permissionCollectionTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected $companyUserCollectionTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransferMock;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    protected $companyUserTransferMocks;

    /**
     * @var int
     */
    protected $idCompanyUser;

    /**
     * @var int
     */
    protected $fkCompany;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PermissionTransfer
     */
    protected $permissionTransferMock;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected $permissionTransferMocks;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var int
     */
    protected $idCompany;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->permissionFacadeInterfaceMock = $this->getMockBuilder(PermissionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserFacadeInterfaceMock = $this->getMockBuilder(CompanyUserFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerIdentifierTransferMock = $this->getMockBuilder(CustomerIdentifierTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionCollectionTransferMock = $this->getMockBuilder(PermissionCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserCollectionTransferMock = $this->getMockBuilder(CompanyUserCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserTransferMock = $this->getMockBuilder(CompanyUserTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserTransferMocks = new ArrayObject([
            $this->companyUserTransferMock,
        ]);

        $this->idCompanyUser = 1;

        $this->fkCompany = 2;

        $this->key = 'key';

        $this->permissionTransferMock = $this->getMockBuilder(PermissionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionTransferMocks = new ArrayObject([
            $this->permissionTransferMock,
        ]);

        $this->idCompany = 3;

        $this->configuration = [
            CompanyUsersCustomerIdentifierExpander::ID_COMPANIES => [
                $this->idCompany,
            ],
        ];

        $this->companyUsersCustomerIdentifierExpander = new CompanyUsersCustomerIdentifierExpander(
            $this->permissionFacadeInterfaceMock,
            $this->companyUserFacadeInterfaceMock
        );
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWithPermissions(): void
    {
        $this->customerIdentifierTransferMock->expects($this->atLeastOnce())
            ->method('getPermissions')
            ->willReturn($this->permissionCollectionTransferMock);

        $this->companyUserFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('getActiveCompanyUsersByCustomerReference')
            ->with($this->customerTransferMock)
            ->willReturn($this->companyUserCollectionTransferMock);

        $this->companyUserCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getCompanyUsers')
            ->willReturn($this->companyUserTransferMocks);

        $this->companyUserTransferMock->expects($this->atLeastOnce())
            ->method('getIdCompanyUser')
            ->willReturn($this->idCompanyUser);

        $this->permissionFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('getPermissionsByIdentifier')
            ->with($this->idCompanyUser)
            ->willReturn($this->permissionCollectionTransferMock);

        $this->companyUserTransferMock->expects($this->atLeastOnce())
            ->method('getFkCompany')
            ->willReturn($this->fkCompany);

        $this->permissionCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPermissions')
            ->willReturn($this->permissionTransferMocks);

        $this->permissionTransferMock->expects($this->atLeastOnce())
            ->method('getKey')
            ->willReturn($this->key);

        $this->permissionTransferMock->expects($this->atLeastOnce())
            ->method('getConfiguration')
            ->willReturn($this->configuration);

        $this->permissionTransferMock->expects($this->atLeastOnce())
            ->method('setConfiguration')
            ->willReturnSelf();

        $this->customerIdentifierTransferMock->expects($this->atLeastOnce())
            ->method('setPermissions')
            ->with($this->permissionCollectionTransferMock)
            ->willReturnSelf();

        $this->assertInstanceOf(
            CustomerIdentifierTransfer::class,
            $this->companyUsersCustomerIdentifierExpander->expandCustomerIdentifierWithPermissions(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWithPermissionsPermissionNull(): void
    {
        $this->customerIdentifierTransferMock->expects($this->atLeastOnce())
            ->method('getPermissions')
            ->willReturn(null);

        $this->companyUserFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('getActiveCompanyUsersByCustomerReference')
            ->with($this->customerTransferMock)
            ->willReturn($this->companyUserCollectionTransferMock);

        $this->companyUserCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getCompanyUsers')
            ->willReturn($this->companyUserTransferMocks);

        $this->companyUserTransferMock->expects($this->atLeastOnce())
            ->method('getIdCompanyUser')
            ->willReturn($this->idCompanyUser);

        $this->permissionFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('getPermissionsByIdentifier')
            ->with($this->idCompanyUser)
            ->willReturn($this->permissionCollectionTransferMock);

        $this->companyUserTransferMock->expects($this->atLeastOnce())
            ->method('getFkCompany')
            ->willReturn($this->fkCompany);

        $this->permissionCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPermissions')
            ->willReturn($this->permissionTransferMocks);

        $this->permissionTransferMock->expects($this->atLeastOnce())
            ->method('getKey')
            ->willReturn($this->key);

        $this->permissionTransferMock->expects($this->atLeastOnce())
            ->method('getConfiguration')
            ->willReturn($this->configuration);

        $this->permissionTransferMock->expects($this->atLeastOnce())
            ->method('setConfiguration')
            ->willReturnSelf();

        $this->customerIdentifierTransferMock->expects($this->atLeastOnce())
            ->method('setPermissions')
            ->willReturnSelf();

        $this->assertInstanceOf(
            CustomerIdentifierTransfer::class,
            $this->companyUsersCustomerIdentifierExpander->expandCustomerIdentifierWithPermissions(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }
}
