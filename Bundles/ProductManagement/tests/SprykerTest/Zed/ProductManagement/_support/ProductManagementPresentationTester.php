<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement;

use Codeception\Actor;
use Codeception\Scenario;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyFormTypePlugin;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

/**
 * Inherited Methods
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
class ProductManagementPresentationTester extends Actor
{
    use _generated\ProductManagementPresentationTesterActions;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->amZed();
        $this->amLoggedInUser();
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin()
    {
        $this->setDependency(ProductManagementDependencyProvider::PLUGIN_MONEY_FORM_TYPE, function () {
            return new MoneyFormTypePlugin();
        });
    }

    /**
     * Define custom actions here
     */

    /**
     * @return void
     */
    public function registerProductManagementStoreRelationFormTypePlugin()
    {
        $this->setDependency(ProductManagementDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return new StoreRelationToggleFormTypePlugin();
        });
    }
}
