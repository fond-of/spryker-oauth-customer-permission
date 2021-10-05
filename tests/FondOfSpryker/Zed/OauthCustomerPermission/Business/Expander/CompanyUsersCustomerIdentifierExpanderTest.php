<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander;

use ArrayObject;
use Codeception\Test\Unit;
use FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepository;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

class CompanyUsersCustomerIdentifierExpanderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $repositoryMock;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerTransferMock;

    /**
     * @var \Generated\Shared\Transfer\CustomerIdentifierTransfer|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerIdentifierTransferMock;

    /**
     * @var \Generated\Shared\Transfer\PermissionTransfer[]|\PHPUnit\Framework\MockObject\MockObject[]
     */
    protected $permissionTransferMocks;

    /**
     * @var \Generated\Shared\Transfer\PermissionCollectionTransfer|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $permissionCollectionTransferMock;

    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpander
     */
    protected $companyUsersCustomerIdentifierExpander;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->repositoryMock = $this->getMockBuilder(OauthCustomerPermissionRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerIdentifierTransferMock = $this->getMockBuilder(CustomerIdentifierTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionTransferMocks = [
            $this->getMockBuilder(PermissionTransfer::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->permissionCollectionTransferMock = $this->getMockBuilder(PermissionCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUsersCustomerIdentifierExpander = new CompanyUsersCustomerIdentifierExpander(
            $this->repositoryMock
        );
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWithPermissions(): void
    {
        $idCustomer = 1;
        $permissionTransferMocks = $this->permissionTransferMocks;

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getIdCustomer')
            ->willReturn($idCustomer);

        $this->repositoryMock->expects(static::atLeastOnce())
            ->method('findPermissionsByIdCustomer')
            ->with($idCustomer)
            ->willReturn($this->permissionTransferMocks);

        $this->customerIdentifierTransferMock->expects(static::atLeastOnce())
            ->method('getPermissions')
            ->willReturn($this->permissionCollectionTransferMock);

        $this->permissionCollectionTransferMock->expects(static::atLeastOnce())
            ->method('setPermissions')
            ->with(
                static::callback(
                    static function (ArrayObject $permissionTransfers) use ($permissionTransferMocks) {
                        return $permissionTransfers->getArrayCopy() == $permissionTransferMocks;
                    }
                )
            )->willReturn($this->permissionCollectionTransferMock);

        $this->customerIdentifierTransferMock->expects(static::atLeastOnce())
            ->method('setPermissions')
            ->with($this->permissionCollectionTransferMock)
            ->willReturn($this->customerIdentifierTransferMock);

        static::assertEquals(
            $this->customerIdentifierTransferMock,
            $this->companyUsersCustomerIdentifierExpander->expandCustomerIdentifierWithPermissions(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWithPermissionsWithoutIdCustomer(): void
    {
        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getIdCustomer')
            ->willReturn(null);

        $this->repositoryMock->expects(static::never())
            ->method('findPermissionsByIdCustomer');

        $this->customerIdentifierTransferMock->expects(static::never())
            ->method('getPermissions');

        $this->customerIdentifierTransferMock->expects(static::never())
            ->method('setPermissions');

        static::assertEquals(
            $this->customerIdentifierTransferMock,
            $this->companyUsersCustomerIdentifierExpander->expandCustomerIdentifierWithPermissions(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWithPermissionsWithoutPredefinedPermission(): void
    {
        $idCustomer = 1;
        $permissionTransferMocks = $this->permissionTransferMocks;

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getIdCustomer')
            ->willReturn($idCustomer);

        $this->repositoryMock->expects(static::atLeastOnce())
            ->method('findPermissionsByIdCustomer')
            ->with($idCustomer)
            ->willReturn($this->permissionTransferMocks);

        $this->customerIdentifierTransferMock->expects(static::atLeastOnce())
            ->method('getPermissions')
            ->willReturn(null);

        $this->customerIdentifierTransferMock->expects(static::atLeastOnce())
            ->method('setPermissions')
            ->with(static::callback(
                static function (PermissionCollectionTransfer $permissionCollectionTransfer) use ($permissionTransferMocks) {
                    return $permissionCollectionTransfer->getPermissions()->getArrayCopy() == $permissionTransferMocks;
                }
            ))
            ->willReturn($this->customerIdentifierTransferMock);

        static::assertEquals(
            $this->customerIdentifierTransferMock,
            $this->companyUsersCustomerIdentifierExpander->expandCustomerIdentifierWithPermissions(
                $this->customerIdentifierTransferMock,
                $this->customerTransferMock
            )
        );
    }
}
