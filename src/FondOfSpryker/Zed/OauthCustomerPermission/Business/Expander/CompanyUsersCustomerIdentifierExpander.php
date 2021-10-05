<?php

declare(strict_types = 1);

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander;

use ArrayObject;
use FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepositoryInterface;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;

class CompanyUsersCustomerIdentifierExpander implements CompanyUsersCustomerIdentifierExpanderInterface
{
    /**
     * @var \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepositoryInterface
     */
    protected $repository;

    /**
     * @param \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepositoryInterface $repository
     */
    public function __construct(OauthCustomerPermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifierWithPermissions(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        $idCustomer = $customerTransfer->getIdCustomer();

        if ($idCustomer === null) {
            return $customerIdentifierTransfer;
        }

        $permissionTransfers = new ArrayObject($this->repository->findPermissionsByIdCustomer($idCustomer));

        $originalPermissionCollectionTransfer = $this->getOriginalPermissionCollection($customerIdentifierTransfer);

        $originalPermissionCollectionTransfer->setPermissions($permissionTransfers);

        return $customerIdentifierTransfer->setPermissions(
            $originalPermissionCollectionTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function getOriginalPermissionCollection(
        CustomerIdentifierTransfer $customerIdentifierTransfer
    ): PermissionCollectionTransfer {
        $originalPermissionCollectionTransfer = $customerIdentifierTransfer->getPermissions();

        if ($originalPermissionCollectionTransfer === null) {
            $originalPermissionCollectionTransfer = new PermissionCollectionTransfer();
        }

        return $originalPermissionCollectionTransfer;
    }
}
