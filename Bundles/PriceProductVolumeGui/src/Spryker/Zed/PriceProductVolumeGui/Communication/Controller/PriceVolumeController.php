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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 */
class PriceVolumeController extends AbstractController
{
    protected const REQUEST_PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const REQUEST_PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';
    protected const REQUEST_PARAM_ID_PRODUCT = 'id-product';
    protected const REQUEST_PARAM_STORE_NAME = 'store-name';
    protected const REQUEST_PARAM_CURRENCY_CODE = 'currency-code';
    protected const REQUEST_PARAM_PRICE_DIMENSION = 'price-dimension';
    protected const REQUEST_PARAM_SAVE_AND_EXIT = 'save_and_exit';
    protected const REQUEST_PARAM_SKU = 'sku';

    protected const PRICE_PRODUCT_VOLUME_EDIT_URL = '/price-product-volume-gui/price-volume/edit';
    protected const PRODUCT_CONCRETE_EDIT_URL = '/product-management/edit/variant';
    protected const PRODUCT_ABSTRACT_EDIT_URL = '/product-management/edit';

    protected const MESSAGE_VOLUME_PRICES_UPDATE_SUCCESS = 'Volume prices successfully saved.';
    protected const PARAM_URL_FRAGMENT = 'fragment';
    protected const PARAM_URL_FRAGMENT_DEFAULT_VALUE = 'tab-content-%s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createPriceVolumeCollectionDataProvider();
        $priceProductTransfer = $this->getPriceProductTransfer($request, $dataProvider);
        $priceVolumeCollectionFormType = $this->getPriceVolumeCollectionFormType($request, $dataProvider, $priceProductTransfer);

        if ($priceVolumeCollectionFormType->isSubmitted() && $priceVolumeCollectionFormType->isValid()) {
            $this->executeAction($priceVolumeCollectionFormType, $priceProductTransfer);

            if ($request->request->has(static::REQUEST_PARAM_SAVE_AND_EXIT)) {
                return $this->redirectResponse($this->getExitUrl($request));
            }

            return $this->redirectResponse($this->getEditUrl($request));
        }

        return $this->viewResponse([
            'form' => $priceVolumeCollectionFormType->createView(),
            'price_product' => $priceProductTransfer,
            'store_name' => $request->get(static::REQUEST_PARAM_STORE_NAME),
            'back_url' => $this->getExitUrl($request),
            'product_sku' => $request->get(static::REQUEST_PARAM_SKU),
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
        $priceVolumeCollectionFormType = $this->getPriceVolumeCollectionFormType($request, $dataProvider, $priceProductTransfer);

        if ($priceVolumeCollectionFormType->isSubmitted() && $priceVolumeCollectionFormType->isValid()) {
            $priceProductTransfer = $this->executeAction($priceVolumeCollectionFormType, $priceProductTransfer);
            $priceVolumeCollectionFormType = $this->getPriceVolumeCollectionFormType($request, $dataProvider, $priceProductTransfer);

            if ($request->request->has(static::REQUEST_PARAM_SAVE_AND_EXIT)) {
                return $this->redirectResponse($this->getExitUrl($request));
            }
        }

        return $this->viewResponse([
            'form' => $priceVolumeCollectionFormType->createView(),
            'price_product' => $priceProductTransfer,
            'store_name' => $request->get(static::REQUEST_PARAM_STORE_NAME),
            'back_url' => $this->getExitUrl($request),
            'product_sku' => $request->get(static::REQUEST_PARAM_SKU),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $priceVolumeCollectionFormType
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executeAction(FormInterface $priceVolumeCollectionFormType, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer = $this->getFactory()
            ->createPriceVolumeCollectionFormHandler()
            ->savePriceProduct(
                $priceVolumeCollectionFormType->getData(),
                $priceProductTransfer
            );

        $this->addSuccessMessage(static::MESSAGE_VOLUME_PRICES_UPDATE_SUCCESS);

        return $priceProductTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider $dataProvider
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getPriceVolumeCollectionFormType(Request $request, PriceVolumeCollectionDataProvider $dataProvider, PriceProductTransfer $priceProductTransfer): FormInterface
    {
        $priceVolumeCollectionFormType = $this->getFactory()
            ->getPriceVolumeCollectionFormType(
                $dataProvider->getData(
                    $priceProductTransfer,
                    $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
                    $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE)
                ),
                $dataProvider->getOptions($request->get(static::REQUEST_PARAM_CURRENCY_CODE))
            )->handleRequest($request);

        return $priceVolumeCollectionFormType;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider $dataProvider
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function getPriceProductTransfer(Request $request, PriceVolumeCollectionDataProvider $dataProvider): PriceProductTransfer
    {
        $priceProductTransfer = $dataProvider->getPriceProductTransfer(
            $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
            $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE),
            $request->get(static::REQUEST_PARAM_STORE_NAME),
            $request->get(static::REQUEST_PARAM_CURRENCY_CODE),
            $request->get(static::REQUEST_PARAM_PRICE_DIMENSION, [])
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
        $query = [
            static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT => $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
            static::REQUEST_PARAM_ID_PRODUCT_CONCRETE => $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE),
            static::REQUEST_PARAM_STORE_NAME => $request->get(static::REQUEST_PARAM_STORE_NAME),
            static::REQUEST_PARAM_CURRENCY_CODE => $request->get(static::REQUEST_PARAM_CURRENCY_CODE),
            static::REQUEST_PARAM_PRICE_DIMENSION => $request->get(static::REQUEST_PARAM_PRICE_DIMENSION, []),
        ];

        return $this->generateUrl(static::PRICE_PRODUCT_VOLUME_EDIT_URL, $query);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getExitUrl(Request $request): string
    {
        if ($request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE)) {
            return $this->getConcreteProductExitUrl($request);
        }

        return $this->getAbstractProductExitUrl($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getConcreteProductExitUrl(Request $request): string
    {
        $query = [
            static::REQUEST_PARAM_ID_PRODUCT => $request->get(static::REQUEST_PARAM_ID_PRODUCT_CONCRETE),
            static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT => $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
        ];

        return $this->generateUrl(static::PRODUCT_CONCRETE_EDIT_URL, array_merge($query, $this->getDefaultProductExitUrlQuery($request)), $this->getUrlOptions('price'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getAbstractProductExitUrl(Request $request): string
    {
        $query = [
            static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT => $request->get(static::REQUEST_PARAM_ID_PRODUCT_ABSTRACT),
        ];

        return $this->generateUrl(static::PRODUCT_ABSTRACT_EDIT_URL, array_merge($query, $this->getDefaultProductExitUrlQuery($request)), $this->getUrlOptions('price_and_tax'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getDefaultProductExitUrlQuery(Request $request): array
    {
        return [
            static::REQUEST_PARAM_PRICE_DIMENSION => $request->get(static::REQUEST_PARAM_PRICE_DIMENSION, []),
        ];
    }

    /**
     * @param string $url
     * @param array $query
     * @param array $options
     *
     * @return string
     */
    protected function generateUrl(string $url, array $query = [], array $options = []): string
    {
        return urldecode(Url::generate($url, $query, $options)->build());
    }

    /**
     * @param string $fragment
     *
     * @return array
     */
    protected function getUrlOptions(string $fragment): array
    {
        return [
            static::PARAM_URL_FRAGMENT => sprintf(static::PARAM_URL_FRAGMENT_DEFAULT_VALUE, $fragment),
        ];
    }
}
