<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement;

use Codeception\Actor;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Service\UtilNumber\UtilNumberServiceInterface;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyFormTypePlugin;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;
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
 * @SuppressWarnings(\SprykerTest\Zed\ProductManagement\PHPMD)
 */
class ProductManagementCommunicationTester extends Actor
{
    use _generated\ProductManagementCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureProductAbstractTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductAbstractQuery());
    }

    /**
     * @return \Spryker\Service\UtilNumber\UtilNumberServiceInterface
     */
    public function getUtilService(): UtilNumberServiceInterface
    {
        return $this->getLocator()->utilNumber()->service();
    }

    /**
     * @return string
     */
    public function getCurrentLocaleName(): string
    {
        return $this->getLocator()
            ->locale()
            ->facade()
            ->getCurrentLocale()
            ->getLocaleName();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return void
     */
    public function registerProductManagementStoreRelationFormTypePlugin(): void
    {
        $this->setDependency(ProductManagementDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return new StoreRelationToggleFormTypePlugin();
        });
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePluginWithoutLocale(): void
    {
        $this->setDependency(ProductManagementDependencyProvider::PLUGIN_MONEY_FORM_TYPE, function () {
            return new MoneyFormTypePlugin();
        });
    }
}
