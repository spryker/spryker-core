<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 */
class EditController extends BaseOptionController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->query->get(BaseOptionController::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productOptionGroupTransfer = $this->getFacade()->getProductOptionGroupById($idProductOptionGroup);
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider($productOptionGroupTransfer);

        $productOptionGroupForm = $this->getFactory()->getProductOptionGroupForm($dataProvider);
        $productOptionGroupForm->handleRequest($request);

        if ($productOptionGroupForm->isSubmitted() && $productOptionGroupForm->isValid()) {
            $this->getFacade()->saveProductOptionGroup($productOptionGroupForm->getData());

            $redirectUrl = Url::generate(
                '/product-option/edit/index',
                [
                    BaseOptionController::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $idProductOptionGroup,
                ]
            )->build();

            $this->addSuccessMessage('Product option group modified.');

            return $this->redirectResponse($redirectUrl);
        }

        $productOptionsTable = $this->getFactory()->createProductOptionTable(
            $idProductOptionGroup,
            ProductOptionTable::TABLE_CONTEXT_EDIT
        );

        $productTable = $this->getFactory()->createProductTable($idProductOptionGroup);
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        $optionTabs = $this->getFactory()->createOptionTabs($productOptionGroupForm);

        return [
            'productOptionsTable' => $productOptionsTable->render(),
            'productsTable' => $productTable->render(),
            'productOptionGroup' => $productOptionGroupTransfer,
            'generalForm' => $productOptionGroupForm->createView(),
            'availableLocales' => $availableLocales,
            'optionTabs' => $optionTabs->createView(),
        ];
    }
}
