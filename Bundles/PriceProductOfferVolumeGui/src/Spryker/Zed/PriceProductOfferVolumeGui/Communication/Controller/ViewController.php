<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication\Controller;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
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
     * @phpstan-return array<mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idProductOffer = $this->castId($request->get(
            self::PARAM_ID_PRODUCT_OFFER
        ));
        $storeName = $request->get(self::PARAM_STORE_NAME);
        $currencyCode = $request->get(self::PARAM_CURRENCY_CODE);

        $productOfferCriteriaFilter = (new ProductOfferCriteriaFilterTransfer())
            ->setIdProductOffer($idProductOffer);

        $productOfferTransfer = $this->getFactory()
            ->getProductOfferFacade()
            ->findOne($productOfferCriteriaFilter);

        $response = [
            'backUrl' => $this->generateUrl('/product-offer-gui/view', [
                self::PARAM_ID_PRODUCT_OFFER => $idProductOffer,
            ]),
            'productOfferReference' => $productOfferTransfer->getProductOfferReference(),
        ];

        foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
            if (!$priceProductTransfer->getMoneyValue()) {
                continue;
            }

            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            $priceData = $this->getFactory()
                ->getUtilEncodingService()
                ->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

            if (
                $moneyValueTransfer->getCurrency()->getCode() === $currencyCode
                && $moneyValueTransfer->getStore()->getName() === $storeName
                && isset($priceData['volume_prices'])
            ) {
                $response['priceProduct'] = $priceProductTransfer;
                $response['volumePrices'] = $priceData['volume_prices'];
            }
        }

        return $this->viewResponse($response);
    }

    /**
     * @phpstan-param array<mixed> $query
     * @phpstan-param array<mixed> $options
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
