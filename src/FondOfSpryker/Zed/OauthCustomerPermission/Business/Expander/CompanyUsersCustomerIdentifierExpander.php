<?php

declare(strict_types=1);

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class CompanyUsersCustomerIdentifierExpander implements CompanyUsersCustomerIdentifierExpanderInterface
{
    /**
     * @var \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @var \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\Permission\Business\PermissionFacadeInterface $permissionFacade
     * @param \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        PermissionFacadeInterface $permissionFacade,
        CompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->permissionFacade = $permissionFacade;
        $this->companyUserFacade = $companyUserFacade;
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
        $originalPermissionCollectionTransfer = $this->getOriginalPermissionCollection($customerIdentifierTransfer);

        $companyUserCollection = $this->getCustomersCompanyUsers($customerTransfer->getIdCustomer());

        foreach ($companyUserCollection->getCompanyUsers() as $companyUserTransfer) {
            $permissionCollectionToMerge = $this->permissionFacade->getPermissionsByIdentifier(
                (string) $companyUserTransfer->getIdCompanyUser()
            );

            $originalPermissionCollectionTransfer = $this->mergePermissionCollection(
                $originalPermissionCollectionTransfer,
                $permissionCollectionToMerge
            );
        }

        return $this->addOriginalPermissionCollectionTransferToCustomerIdentifierTransfer(
            $originalPermissionCollectionTransfer,
            $customerIdentifierTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $originalPermissionCollectionTransfer
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    protected function addOriginalPermissionCollectionTransferToCustomerIdentifierTransfer(
        PermissionCollectionTransfer $originalPermissionCollectionTransfer,
        CustomerIdentifierTransfer $customerIdentifierTransfer
    ) : CustomerIdentifierTransfer {
        if ($originalPermissionCollectionTransfer->getPermissions()->count() > 0) {
            $customerIdentifierTransfer->setPermissions($originalPermissionCollectionTransfer);
        }

        return $customerIdentifierTransfer;
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

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected function getCustomersCompanyUsers(int $idCustomer) : CompanyUserCollectionTransfer
    {
        $criteria = new CompanyUserCriteriaFilterTransfer();
        $criteria->setIdCustomer($idCustomer);
        $criteria->setIsActive(true);

        return $this->companyUserFacade->getCompanyUserCollection($criteria);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $originalPermissionCollectionTransfer
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransferToMerge
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function mergePermissionCollection(
        PermissionCollectionTransfer $originalPermissionCollectionTransfer,
        PermissionCollectionTransfer $permissionCollectionTransferToMerge
    ): PermissionCollectionTransfer {
        foreach ($permissionCollectionTransferToMerge->getPermissions() as $permissionToMerge) {
            if ($this->hasPermission($originalPermissionCollectionTransfer, $permissionToMerge)) {
                continue;
            }

            $originalPermissionCollectionTransfer->addPermission($permissionToMerge);
        }

        return $originalPermissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionToMergeTransfer
     *
     * @return bool
     */
    protected function hasPermission(
        PermissionCollectionTransfer $permissionCollectionTransfer,
        PermissionTransfer $permissionToMergeTransfer
    ): bool {
        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            if ($permissionTransfer->getIdPermission() === $permissionToMergeTransfer->getIdPermission()) {
                return true;
            }
        }

        return false;
    }
}
