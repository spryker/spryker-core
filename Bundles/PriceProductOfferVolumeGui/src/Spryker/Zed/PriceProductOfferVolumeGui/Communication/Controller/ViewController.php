<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication\Controller;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\PriceProductOfferVolumeGui\PriceProductOfferVolumeGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductOfferVolumeGui\Communication\PriceProductOfferVolumeGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    protected const PARAM_ID_PRODUCT_OFFER = 'id-product-offer';
    protected const PARAM_STORE_NAME = 'store-name';
    protected const PARAM_CURRENCY_CODE = 'currency-code';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idProductOffer = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_OFFER
        ));
        $storeName = $request->get(static::PARAM_STORE_NAME);
        $currencyCode = $request->get(static::PARAM_CURRENCY_CODE);

        $productOfferCriteria = (new ProductOfferCriteriaTransfer())
            ->setIdProductOffer($idProductOffer);

        $productOfferTransfer = $this->getFactory()
            ->getProductOfferFacade()
            ->findOne($productOfferCriteria);

        $response = [
            'backUrl' => $this->generateUrl(PriceProductOfferVolumeGuiConfig::PRODUCT_OFFER_URL_VIEW, [
                static::PARAM_ID_PRODUCT_OFFER => $idProductOffer,
            ]),
            'productOfferReference' => $productOfferTransfer->getProductOfferReference(),
        ];

        $response = array_merge(
            $response,
            $this->getFactory()
                ->createPriceProductOfferVolumeReader()
                ->getVolumePricesData($productOfferTransfer, $storeName, $currencyCode)
        );

        return $this->viewResponse($response);
    }

    /**
     * @phpstan-param array<string, mixed> $query
     * @phpstan-param array<string, mixed> $options
     *
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
}
