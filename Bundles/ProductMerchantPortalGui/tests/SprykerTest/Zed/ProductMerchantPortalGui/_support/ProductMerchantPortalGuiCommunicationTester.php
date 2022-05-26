<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui;

use Codeception\Actor;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMerger;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\ProductMerchantPortalGuiCommunicationTesterActions;

    /**
     * @param array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface> $priceProductMapperPlugins
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface
     */
    public function createPriceProductMapper(array $priceProductMapperPlugins = []): PriceProductMapperInterface
    {
        return new PriceProductMapper(
            $this->createProductMerchantPortalGuiToPriceProductFacadeBridge(),
            $this->createProductMerchantPortalGuiToCurrencyFacadeBridge(),
            $this->createProductMerchantPortalGuiToMoneyFacadeBridge(),
            $this->createPriceProductMerger(),
            $this->createProductMerchantPortalGuiToUtilEncodingServiceBridge(),
            $priceProductMapperPlugins,
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected function createProductMerchantPortalGuiToPriceProductFacadeBridge(): ProductMerchantPortalGuiToPriceProductFacadeInterface
    {
        return new ProductMerchantPortalGuiToPriceProductFacadeBridge(
            $this->getLocator()->priceProduct()->facade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected function createProductMerchantPortalGuiToCurrencyFacadeBridge(): ProductMerchantPortalGuiToCurrencyFacadeInterface
    {
        return new ProductMerchantPortalGuiToCurrencyFacadeBridge(
            $this->getLocator()->currency()->facade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected function createProductMerchantPortalGuiToMoneyFacadeBridge(): ProductMerchantPortalGuiToMoneyFacadeInterface
    {
        return new ProductMerchantPortalGuiToMoneyFacadeBridge(
            $this->getLocator()->money()->facade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected function createProductMerchantPortalGuiToUtilEncodingServiceBridge(): ProductMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return new ProductMerchantPortalGuiToUtilEncodingServiceBridge(
            $this->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\PriceProductsMergerInterface
     */
    protected function createPriceProductMerger(): PriceProductMergerInterface
    {
        return new PriceProductMerger([]);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface
     */
    public function createSetIdProductAbstractPriceProductMapperPluginMock(): PriceProductMapperPluginInterface
    {
        return Stub::makeEmpty(
            PriceProductMapperPluginInterface::class,
            [
                'mapRequestDataToPriceProductCriteriaTransfer' =>
                    function (
                        array $data,
                        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
                    ) {
                        return $priceProductCriteriaTransfer->setIdProductAbstract($data['idProductAbstract']);
                    },
            ],
        );
    }
}
