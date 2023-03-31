<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption;

use Codeception\Actor;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyCollectionFormTypePlugin as MoneyCollectionFormTypePluginWithoutLocale;
use Spryker\Zed\MoneyGui\Communication\Plugin\Form\MoneyCollectionFormTypePlugin;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

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
class ProductOptionCommunicationTester extends Actor
{
    use _generated\ProductOptionCommunicationTesterActions;

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->getContainer()->set(static::SERVICE_CURRENCY, static::DEFAULT_CURRENCY);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionGroup
     *
     * @return void
     */
    public function createProductOptionValueEntity(string $sku, int $idProductOptionGroup): void
    {
        $productOptionValue = new SpyProductOptionValue();
        $productOptionValue->setSku($sku);
        $productOptionValue->setValue($sku);
        $productOptionValue->setFkProductOptionGroup($idProductOptionGroup);

        $productOptionValue->save();
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin(): void
    {
        $this->setDependency(ProductOptionDependencyProvider::MONEY_COLLECTION_FORM_TYPE_PLUGIN, function () {
            return new MoneyCollectionFormTypePlugin();
        });
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePluginWithoutLocale(): void
    {
        $this->setDependency(ProductOptionDependencyProvider::MONEY_COLLECTION_FORM_TYPE_PLUGIN, function () {
            return new MoneyCollectionFormTypePluginWithoutLocale();
        });
    }
}
