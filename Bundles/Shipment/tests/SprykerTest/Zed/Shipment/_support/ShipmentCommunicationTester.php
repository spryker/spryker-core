<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment;

use Codeception\Actor;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyCollectionFormTypePlugin;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentCommunicationTester extends Actor
{
    use _generated\ShipmentCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin()
    {
        $this->setDependency(ShipmentDependencyProvider::MONEY_COLLECTION_FORM_TYPE_PLUGIN, function () {
            return new MoneyCollectionFormTypePlugin();
        });
    }
}
