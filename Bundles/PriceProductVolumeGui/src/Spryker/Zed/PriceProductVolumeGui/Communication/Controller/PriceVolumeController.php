<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductVolumeGui\Business\PriceProductVolumeGuiFacadeInterface getFacade()
 */
class PriceVolumeController extends AbstractController
{
    protected const REQUEST_PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const REQUEST_PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';
    protected const REQUEST_PARAM_STORE_NAME = 'store-name';
    protected const REQUEST_PARAM_CURRENCY_CODE = 'currency-code';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createPriceVolumeCollectionDataProvider();
        $priceProductTransfer = $dataProvider->getPriceProductTransfer(
            $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
            $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE),
            $request->get(static::REQUEST_PARAM_STORE_NAME),
            $request->get(static::REQUEST_PARAM_CURRENCY_CODE)
        );
        $priceVolumeCollectionFormType = $this->getFactory()
            ->getPriceVolumeCollectionFormType(
                $dataProvider->getData($priceProductTransfer),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($priceVolumeCollectionFormType->isSubmitted() && $priceVolumeCollectionFormType->isValid()) {
            $data = $priceVolumeCollectionFormType->getData();

            $priceProductTransfer = $this->getFactory()
                ->createPriceVolumeCollectionDataMapper()
                ->mapArrayToPriceProductTransfer(
                    $data,
                    $priceProductTransfer
                );

            $this->getFactory()
                ->getPriceProductFacade()
                ->persistPriceProductStore($priceProductTransfer);
        }

        return $this->viewResponse([
            'form' => $priceVolumeCollectionFormType->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        //todo: add

        return $this->viewResponse([]);
    }
}
