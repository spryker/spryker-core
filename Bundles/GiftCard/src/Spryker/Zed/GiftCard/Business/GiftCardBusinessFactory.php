<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business;

use Spryker\Zed\GiftCard\Business\Cart\MetadataExpander;
use Spryker\Zed\GiftCard\Business\Discount\GiftCardDiscountableItemFilter;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCreator;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReader;
use Spryker\Zed\GiftCard\Business\Payment\PaymentMethodFilter;
use Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaver;
use Spryker\Zed\GiftCard\GiftCardDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainer getQueryContainer()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 */
class GiftCardBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    public function createGiftCardReader()
    {
        return new GiftCardReader(
            $this->getQueryContainer(),
            $this->getEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCreatorInterface
     */
    public function createGiftCardCreator()
    {
        return new GiftCardCreator($this->getEncodingService());
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaver
     */
    public function createSalesOrderItemSaver()
    {
        return new SalesOrderItemSaver(
            $this->getGiftCardAttributePlugins(),
            $this->getEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardAttributePluginInterface[]
     */
    protected function getGiftCardAttributePlugins()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::ATTRIBUTE_PROVIDER_PLUGINS);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function getEncodingService()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Cart\MetadataExpanderInterface
     */
    public function createGiftCardMetadataExpander()
    {
        return new MetadataExpander($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Discount\GiftCardDiscountableItemFilterInterface
     */
    public function createGiftCardDiscountableItemFilter()
    {
        return new GiftCardDiscountableItemFilter();
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Payment\PaymentMethodFilter
     */
    public function createPaymentMethodFilter()
    {
        return new PaymentMethodFilter($this->getConfig());
    }

}
