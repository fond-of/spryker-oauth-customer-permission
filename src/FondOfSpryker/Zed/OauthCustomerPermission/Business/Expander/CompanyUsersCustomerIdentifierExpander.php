<?php

declare(strict_types=1);

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

use function in_array;
use function array_key_exists;

class CompanyUsersCustomerIdentifierExpander implements CompanyUsersCustomerIdentifierExpanderInterface
{
    public const ID_COMPANIES = 'id_companies';

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

        $companyUserCollection = $this->getCustomersCompanyUsers($customerTransfer);

        foreach ($companyUserCollection->getCompanyUsers() as $companyUserTransfer) {
            $permissionCollectionToMerge = $this->permissionFacade->getPermissionsByIdentifier(
                (string) $companyUserTransfer->getIdCompanyUser()
            );

            $originalPermissionCollectionTransfer = $this->mergePermissionCollection(
                $originalPermissionCollectionTransfer,
                $permissionCollectionToMerge,
                $companyUserTransfer->getFkCompany()
            );
        }

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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected function getCustomersCompanyUsers(CustomerTransfer $customerTransfer) : CompanyUserCollectionTransfer
    {
        return $this->companyUserFacade
            ->getActiveCompanyUsersByCustomerReference($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $originalPermissionCollectionTransfer
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransferToMerge
     * @param int $companyId
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function mergePermissionCollection(
        PermissionCollectionTransfer $originalPermissionCollectionTransfer,
        PermissionCollectionTransfer $permissionCollectionTransferToMerge,
        int $companyId
    ): PermissionCollectionTransfer {
        foreach ($permissionCollectionTransferToMerge->getPermissions() as $permissionToMerge) {
            $originalPermissionTransfer = $this->getPermissionTransferFromPermissionCollectionByKey(
                $originalPermissionCollectionTransfer,
                $permissionToMerge->getKey()
            );

            if ($originalPermissionTransfer !== null) {
                $this->addCompanyIdToPermissionTransfer($companyId, $originalPermissionTransfer);
                continue;
            }

            $permissionToMerge = $this->addCompanyIdToPermissionTransfer($companyId, $permissionToMerge);
            $originalPermissionCollectionTransfer->addPermission($permissionToMerge);
        }

        return $originalPermissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    protected function getPermissionTransferFromPermissionCollectionByKey(
        PermissionCollectionTransfer $permissionCollectionTransfer,
        string $permissionKey
    ): ?PermissionTransfer {
        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            if ($permissionTransfer->getKey() === $permissionKey) {
                return $permissionTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $companyId
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    protected function addCompanyIdToPermissionTransfer(
        int $companyId,
        PermissionTransfer $permissionTransfer
    ) : PermissionTransfer {
        $configuration = $permissionTransfer->getConfiguration();

        $companiesArray = [];
        if (array_key_exists(static::ID_COMPANIES, $configuration)) {
            $companiesArray = $configuration[static::ID_COMPANIES];
        }

        if (!in_array($companyId, $companiesArray, true)) {
            $companiesArray[] = $companyId;
        }

        $configuration[static::ID_COMPANIES] = $companiesArray;

        $permissionTransfer->setConfiguration($configuration);

        return $permissionTransfer;
    }
}
