<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Communication\Plugin\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingList\Communication\ShoppingListCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShoppingList\ShoppingListConfig getConfig()
 */
class CompanyBusinessUnitPreDeletePlugin extends AbstractPlugin implements CompanyBusinessUnitPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function preDelete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): void
    {
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        $this->getFacade()->unShareShoppingListWithCompanyBusinessUnit($shoppingListShareRequestTransfer);
    }
}
