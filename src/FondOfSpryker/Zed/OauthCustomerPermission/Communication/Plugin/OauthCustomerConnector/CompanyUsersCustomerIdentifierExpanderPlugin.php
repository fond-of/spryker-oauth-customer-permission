<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Communication\Plugin\OauthCustomerConnector;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin\OauthCustomerIdentifierExpanderPluginInterface;

/**
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\Business\OauthCustomerPermissionFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\OauthCustomerPermissionConfig getConfig()
 */
class CompanyUsersCustomerIdentifierExpanderPlugin extends AbstractPlugin implements OauthCustomerIdentifierExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        return $this->getFacade()
            ->expandCustomerIdentifierWithCompanyUsersPermissions($customerIdentifierTransfer, $customerTransfer);
    }
}
