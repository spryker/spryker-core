<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 */
class CreateController extends BaseOptionController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider();

        $productOptionGroupForm = $this->getFactory()->getProductOptionGroupForm($dataProvider);
        $productOptionGroupForm->handleRequest($request);

        if ($productOptionGroupForm->isSubmitted() && $productOptionGroupForm->isValid()) {
            /** @var \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer */
            $productOptionGroupTransfer = $productOptionGroupForm->getData();
            $idProductOptionGroup = $this->getFacade()->saveProductOptionGroup($productOptionGroupTransfer);

            $redirectUrl = Url::generate(
                '/product-option/edit/index',
                [
                    BaseOptionController::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $idProductOptionGroup,
                ],
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
            'optionTabs' => $optionTabs->createView(),
        ];
    }
}
