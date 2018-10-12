<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider;
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

    protected const PRICE_PRODUCT_VOLUME_EDIT_URL = '/price-product-volume-gui/price-volume/edit';
    protected const MESSAGE_VOLUME_PRICES_UPDATE_SUCCESS = 'Volume prices successfully saved.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createPriceVolumeCollectionDataProvider();
        $priceProductTransfer = $this->getPriceProductTransfer($request, $dataProvider);
        $priceVolumeCollectionFormType = $this->getFactory()
            ->getPriceVolumeCollectionFormType(
                $dataProvider->getData($priceProductTransfer),
                $dataProvider->getOptions($request->get(static::REQUEST_PARAM_CURRENCY_CODE))
            )->handleRequest($request);

        if ($priceVolumeCollectionFormType->isSubmitted() && $priceVolumeCollectionFormType->isValid()) {
            $this->savePriceProduct(
                $priceVolumeCollectionFormType->getData(),
                $priceProductTransfer
            );
            $this->addSuccessMessage(static::MESSAGE_VOLUME_PRICES_UPDATE_SUCCESS);

            return $this->redirectResponse($this->getEditUrl($request));
        }

        return $this->viewResponse([
            'form' => $priceVolumeCollectionFormType->createView(),
            'price_product' => $priceProductTransfer,
            'store_name' => $request->get(static::REQUEST_PARAM_STORE_NAME),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createPriceVolumeCollectionDataProvider();
        $priceProductTransfer = $this->getPriceProductTransfer($request, $dataProvider);
        $priceVolumeCollectionFormType = $this->getFactory()
            ->getPriceVolumeCollectionFormType(
                $dataProvider->getData($priceProductTransfer),
                $dataProvider->getOptions($request->get(static::REQUEST_PARAM_CURRENCY_CODE))
            )->handleRequest($request);

        if ($priceVolumeCollectionFormType->isSubmitted() && $priceVolumeCollectionFormType->isValid()) {
            $priceProductTransfer = $this->savePriceProduct(
                $priceVolumeCollectionFormType->getData(),
                $priceProductTransfer
            );

            $priceVolumeCollectionFormType = $this->getFactory()
                ->getPriceVolumeCollectionFormType(
                    $dataProvider->getData($priceProductTransfer),
                    $dataProvider->getOptions($request->get(static::REQUEST_PARAM_CURRENCY_CODE))
                );
            $this->addSuccessMessage(static::MESSAGE_VOLUME_PRICES_UPDATE_SUCCESS);
        }

        return $this->viewResponse([
            'form' => $priceVolumeCollectionFormType->createView(),
            'price_product' => $priceProductTransfer,
            'store_name' => $request->get(static::REQUEST_PARAM_STORE_NAME),
        ]);
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function savePriceProduct(array $data, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer = $this->getFactory()
            ->createPriceVolumeCollectionDataMapper()
            ->mapArrayToPriceProductTransfer(
                $data,
                $priceProductTransfer
            );

        $priceProductTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->persistPriceProductStore($priceProductTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider $dataProvider
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function getPriceProductTransfer(Request $request, PriceVolumeCollectionDataProvider $dataProvider): PriceProductTransfer
    {
        $dataProvider = $this->getFactory()->createPriceVolumeCollectionDataProvider();
        $priceProductTransfer = $dataProvider->getPriceProductTransfer(
            $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
            $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE),
            $request->get(static::REQUEST_PARAM_STORE_NAME),
            $request->get(static::REQUEST_PARAM_CURRENCY_CODE)
        );

        return $priceProductTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getEditUrl(Request $request): string
    {
        $redirectUrl = Url::generate(
            static::PRICE_PRODUCT_VOLUME_EDIT_URL,
            [
                static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT => $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
                static::REQUEST_PARAM_ID_PRODUCT_CONCRETE => $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE),
                static::REQUEST_PARAM_STORE_NAME => $request->get(static::REQUEST_PARAM_STORE_NAME),
                static::REQUEST_PARAM_CURRENCY_CODE => $request->get(static::REQUEST_PARAM_CURRENCY_CODE),
            ]
        )->build();

        return $redirectUrl;
    }
}
