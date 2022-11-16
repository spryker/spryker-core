<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentGui;

use Codeception\Actor;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyCollectionFormTypePlugin as MoneyCollectionFormTypePluginWithoutLocale;
use Spryker\Zed\MoneyGui\Communication\Plugin\Form\MoneyCollectionFormTypePlugin;
use Spryker\Zed\ShipmentGui\ShipmentGuiDependencyProvider;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentGuiCommunicationTester extends Actor
{
    use _generated\ShipmentGuiCommunicationTesterActions;

    /**
     * @return void
     */
    public function registerProductManagementStoreRelationFormTypePlugin(): void
    {
        $this->setDependency(ShipmentGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return new StoreRelationToggleFormTypePlugin();
        });
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin(): void
    {
        $this->setDependency(ShipmentGuiDependencyProvider::PLUGIN_MONEY_COLLECTION_FORM_TYPE, function () {
            return new MoneyCollectionFormTypePlugin();
        });
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePluginWithoutLocale(): void
    {
        $this->setDependency(ShipmentGuiDependencyProvider::PLUGIN_MONEY_COLLECTION_FORM_TYPE, function () {
            return new MoneyCollectionFormTypePluginWithoutLocale();
        });
    }
}
