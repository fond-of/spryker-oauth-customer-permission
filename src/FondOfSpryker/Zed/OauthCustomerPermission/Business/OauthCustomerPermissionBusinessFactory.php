<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business;

use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpander;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepositoryInterface getRepository()
 */
class OauthCustomerPermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface
     */
    public function createCompanyUsersCustomerIdentifierExpander(): CompanyUsersCustomerIdentifierExpanderInterface
    {
        return new CompanyUsersCustomerIdentifierExpander($this->getRepository());
    }
}
