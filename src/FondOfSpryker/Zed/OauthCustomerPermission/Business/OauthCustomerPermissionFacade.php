<?php

declare(strict_types = 1);

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionBusinessFactory getFactory()
 */
class OauthCustomerPermissionFacade extends AbstractFacade implements OauthCustomerPermissionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifierWithCompanyUsersPermissions(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        return $this->getFactory()
            ->createCompanyUsersCustomerIdentifierExpander()
            ->expandCustomerIdentifierWithPermissions($customerIdentifierTransfer, $customerTransfer);
    }
}
