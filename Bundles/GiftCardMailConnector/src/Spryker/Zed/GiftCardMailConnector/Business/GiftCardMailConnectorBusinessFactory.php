<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business;

use Spryker\Zed\GiftCardMailConnector\Business\Carrier\GiftCardCarrier;
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
            $this->getGiftCardQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailInterface
     */
    protected function getMailFacade()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::MAIL_FACADE);
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerInterface
     */
    protected function getCustomerFacade()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::CUSTOMER_FACADE);
    }

    /**
     * @return \Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface
     */
    protected function getGiftCardQueryContainer()
    {
        return $this->getProvidedDependency(GiftCardMailConnectorDependencyProvider::GIFT_CARD_QUERY_CONTAINER);
    }

}
