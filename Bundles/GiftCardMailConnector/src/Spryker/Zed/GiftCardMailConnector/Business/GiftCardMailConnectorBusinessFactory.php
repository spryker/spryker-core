<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business;

use Spryker\Zed\GiftCardMailConnector\Business\Carrier\GiftCardCarrier;
use Spryker\Zed\GiftCardMailConnector\Business\Checkout\GiftCardUsageMailer;
use Spryker\Zed\GiftCardMailConnector\GiftCardMailConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GiftCardMailConnector\GiftCardMailConnectorConfig getConfig()
 */
class GiftCardMailConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Business\Carrier\GiftCardCarrierInterface
     */
    public function createGiftCardCarrier()
    {
        return new GiftCardCarrier(
            $this->getMailFacade(),
            $this->getCustomerFacade(),
            $this->getGiftCardFacade(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Business\Checkout\GiftCardUsageMailerInterface
     */
    public function createGiftCardUsageMailer()
    {
        return new GiftCardUsageMailer(
            $this->getMailFacade(),
            $this->getGiftCardFacade()
        );
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface
     */
    protected function getMailFacade()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface
     */
    protected function getCustomerFacade()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface
     */
    protected function getGiftCardFacade()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::FACADE_GIFT_CARD);
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeInterface
     */
    protected function getSalesFacade()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::FACADE_SALES);
    }
}
