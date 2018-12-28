<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractProductSetController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCreateFormDataProvider();

        $productSetForm = $this->getFactory()
            ->getCreateProductSetForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($productSetForm->isSubmitted() && $productSetForm->isValid()) {
            $productSetTransfer = $this->getFactory()
                ->createCreateFormDataToTransferMapper()
                ->mapData($productSetForm);

            $productSetTransfer = $this->getFactory()
                ->getProductSetFacade()
                ->createProductSet($productSetTransfer);

            $this->addSuccessMessage('Product Set "%s" created successfully.', [
                '%s' => $productSetTransfer->getLocalizedData()[0]->getProductSetData()->getName(),
            ]);

            return $this->redirectResponse(
                Url::generate('/product-set-gui/view', [
                    static::PARAM_ID => $productSetTransfer->getIdProductSet(),
                ])->build()
            );
        }

        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();

        return $this->viewResponse([
            'productSetForm' => $productSetForm->createView(),
            'productSetFormTabs' => $this->getFactory()->createProductSetFormTabs()->createView(),
            'localeCollection' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'productTable' => $this->getFactory()->createProductTable($localeTransfer)->render(),
        ]);
    }
}
