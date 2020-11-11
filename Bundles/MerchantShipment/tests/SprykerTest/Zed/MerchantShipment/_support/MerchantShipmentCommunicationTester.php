<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantShipment;

use Codeception\Actor;
use Spryker\Zed\MerchantShipment\Communication\Plugin\Cart\MerchantShipmentItemExpanderPlugin;
use Spryker\Zed\MerchantShipment\Communication\Plugin\Quote\MerchantShipmentQuoteExpanderPlugin;

/**
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
class MerchantShipmentCommunicationTester extends Actor
{
    use _generated\MerchantShipmentCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\MerchantShipment\Communication\Plugin\Quote\MerchantShipmentQuoteExpanderPlugin
     */
    public function createMerchantShipmentQuoteExpanderPlugin(): MerchantShipmentQuoteExpanderPlugin
    {
        return new MerchantShipmentQuoteExpanderPlugin();
    }

    /**
     * @return \Spryker\Zed\MerchantShipment\Communication\Plugin\Cart\MerchantShipmentItemExpanderPlugin
     */
    public function createMerchantShipmentItemExpanderPlugin(): MerchantShipmentItemExpanderPlugin
    {
        return new MerchantShipmentItemExpanderPlugin();
    }
}
