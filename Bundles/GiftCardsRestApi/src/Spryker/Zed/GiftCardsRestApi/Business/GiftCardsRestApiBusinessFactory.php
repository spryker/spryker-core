<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardsRestApi\Business;

use Spryker\Zed\GiftCardsRestApi\Business\Writer\GiftCardShipmentWriter;
use Spryker\Zed\GiftCardsRestApi\Business\Writer\GiftCardShipmentWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GiftCardsRestApi\GiftCardsRestApiConfig getConfig()
 */
class GiftCardsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GiftCardsRestApi\Business\Writer\GiftCardShipmentWriterInterface
     */
    public function createGiftCardShipmentWriter(): GiftCardShipmentWriterInterface
    {
        return new GiftCardShipmentWriter();
    }
}
