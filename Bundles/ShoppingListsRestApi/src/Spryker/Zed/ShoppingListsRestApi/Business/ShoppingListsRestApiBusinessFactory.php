<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReader;
use Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemAdder;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemAdderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemResponseBuilder;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemResponseBuilderInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig getConfig()
 */
class ShoppingListsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemAdderInterface
     */
    public function createShoppingListItemAdder(): ShoppingListItemAdderInterface
    {
        return new ShoppingListItemAdder(
            $this->createCompanyUserReader(),
            $this->getShoppingListFacade(),
            $this->createShoppingListItemResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader($this->getCompanyUserFacade());
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemResponseBuilderInterface
     */
    public function createShoppingListItemResponseBuilder(): ShoppingListItemResponseBuilderInterface
    {
        return new ShoppingListItemResponseBuilder();
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): ShoppingListsRestApiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListsRestApiDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    public function getShoppingListFacade(): ShoppingListsRestApiToShoppingListFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListsRestApiDependencyProvider::FACADE_SHOPPING_LIST);
    }
}
