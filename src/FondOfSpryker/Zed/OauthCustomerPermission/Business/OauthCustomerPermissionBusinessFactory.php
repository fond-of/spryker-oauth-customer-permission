<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Business;

use FondOfSpryker\Zed\OauthCustomerPermission\OauthCustomerPermissionDependencyProvider;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpander;
use FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class OauthCustomerPermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\OauthCustomerPermission\Business\Expander\CompanyUsersCustomerIdentifierExpanderInterface
     */
    public function createCompanyUsersCustomerIdentifierExpander(): CompanyUsersCustomerIdentifierExpanderInterface
    {
        return new CompanyUsersCustomerIdentifierExpander(
            $this->getPermissionFacade(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    public function getPermissionFacade(): PermissionFacadeInterface
    {
        return $this->getProvidedDependency(OauthCustomerPermissionDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthCustomerPermissionDependencyProvider::FACADE_COMPANY_USER);
    }
}
