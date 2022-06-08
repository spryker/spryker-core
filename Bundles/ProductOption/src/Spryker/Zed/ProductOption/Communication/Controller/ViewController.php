<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 */
class ViewController extends BaseOptionController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idProductOptionGroup = $this->castId(
            $request->query->get(BaseOptionController::URL_PARAM_ID_PRODUCT_OPTION_GROUP),
        );

        $productOptionsTable = $this->getFactory()->createProductOptionTable(
            $idProductOptionGroup,
            ProductOptionTable::TABLE_CONTEXT_VIEW,
        );

        $productOptionGroupTransfer = $this->getFacade()->getProductOptionGroupById($idProductOptionGroup);
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        $taxSetTransfer = new TaxSetTransfer();
        if ($productOptionGroupTransfer->getFkTaxSet()) {
            $taxSetTransfer = $this->getFactory()->getTaxFacade()->getTaxSet($productOptionGroupTransfer->getFkTaxSet());
        }

        return [
            'productOptionGroup' => $productOptionGroupTransfer,
            'availableLocales' => $availableLocales,
            'productOptionsTable' => $productOptionsTable->render(),
            'taxSet' => $taxSetTransfer,
        ];
    }
}
