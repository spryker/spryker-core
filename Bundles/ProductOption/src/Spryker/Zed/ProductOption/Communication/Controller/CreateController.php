<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Shared\Url\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class CreateController extends BaseOptionController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider();

        $productOptionGroupForm = $this->getFactory()->createProductOptionGroup($dataProvider);
        $productOptionGroupForm->handleRequest($request);

        if ($productOptionGroupForm->isValid()) {
            $productOptionGroupTransfer = $productOptionGroupForm->getData();
            $idProductOptionGroup = $this->getFacade()->saveProductOptionGroup($productOptionGroupTransfer);

            $redirectUrl = Url::generate(
                '/product-option/edit/index',
                [
                    BaseOptionController::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $idProductOptionGroup
                ]
            )->build();

            $this->addSuccessMessage('Product option group created.');

            return $this->redirectResponse($redirectUrl);
        }

        $productTable = $this->getFactory()->createProductTable();

        $optionTabs = $this->getFactory()->createOptionTabs($productOptionGroupForm);

        return [
            'generalForm' => $productOptionGroupForm->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'productsTable' => $productTable->render(),
            'optionTabs' => $optionTabs->createView()
        ];
    }

}
