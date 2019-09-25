<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Communication\Plugin\CompanyUserExtension;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingList\Communication\ShoppingListCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShoppingList\ShoppingListConfig getConfig()
 */
class ShoppingListCompanyUserPreDeletePlugin extends AbstractPlugin implements CompanyUserPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Un-shares shopping lists of company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function preDelete(CompanyUserTransfer $companyUserTransfer): void
    {
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $this->getFacade()->unShareCompanyUserShoppingLists($shoppingListShareRequestTransfer);
    }
}
