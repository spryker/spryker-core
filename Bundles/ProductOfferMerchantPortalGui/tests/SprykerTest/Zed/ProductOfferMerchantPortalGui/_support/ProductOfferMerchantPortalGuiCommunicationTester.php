<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ProductOfferMerchantPortalGui;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferMerchantPortalGui\PHPMD)
 */
class ProductOfferMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\ProductOfferMerchantPortalGuiCommunicationTesterActions;

    /**
     * @param array<string, object> $mocks
     *
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory
     */
    public function createProductOfferMerchantPortalGuiCommunicationFactoryMock(array $mocks = []): ProductOfferMerchantPortalGuiCommunicationFactory
    {
        $params = [
            'resolveDependencyProvider' => function () {
                return (new ProductOfferMerchantPortalGuiDependencyProvider());
            },
        ];

        foreach ($mocks as $key => $mock) {
            $params[$key] = function () use ($mock) {
                return $mock;
            };
        }

        return Stub::make(ProductOfferMerchantPortalGuiCommunicationFactory::class, $params);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface
     */
    public function createPriceProductOfferDataProviderMock(PriceProductTransfer $priceProductTransfer): PriceProductOfferDataProviderInterface
    {
        return Stub::make(
            PriceProductOfferDataProvider::class,
            [
                'getPriceProductTransfers' => function () use ($priceProductTransfer) {
                    return new ArrayObject([$priceProductTransfer]);
                },
            ],
        );
    }
}
