<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Kernel\Container as ZedContainer;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Search\ProductConcretePageMapPlugin;
use Spryker\Zed\Search\SearchDependencyProvider;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

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
class ProductPageSearchBusinessTester extends Actor
{
    use _generated\ProductPageSearchBusinessTesterActions;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer;
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer;
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[];
     */
    protected $storeTransfers;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->addDependencies();
        $this->setUpData();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstractTransfer(): ProductAbstractTransfer
    {
        return $this->productAbstractTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoreTransfers(): array
    {
        return $this->storeTransfers;
    }

    /**
     * @return string[]
     */
    public function getStoreNames(): array
    {
        $storeNames = [];

        foreach ($this->getStoreTransfers() as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }

    /**
     * @return void
     */
    protected function setUpData(): void
    {
        $this->productConcreteTransfer = $this->haveProduct();
        $this->productAbstractTransfer = $this->getProductFacade()->findProductAbstractById(
            $this->productConcreteTransfer->getFkProductAbstract()
        );

        $localizedAttributes = $this->generateLocalizedAttributes();
        $this->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->addStoreRelationToProductAbstracts($this->productAbstractTransfer);
        $this->addLocalizedAttributesToProductConcrete($this->productConcreteTransfer, $localizedAttributes);
    }

    /**
     * @return void
     */
    protected function addDependencies(): void
    {
        $this->addRabbitMqDependency();
        $this->addPluginSearchPageMapsDependency();
    }

    /**
     * @return void
     */
    protected function addRabbitMqDependency(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    protected function addPluginSearchPageMapsDependency(): void
    {
        $this->setDependency(SearchDependencyProvider::PLUGIN_SEARCH_PAGE_MAPS, function (ZedContainer $container) {
            return [
                new ProductConcretePageMapPlugin(),
            ];
        });
    }

    /**
     * @return array
     */
    protected function getIdStores(): array
    {
        $storeIds = [];
        $this->storeTransfers = [];

        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
            $this->storeTransfers[] = $storeTransfer;
        }

        return $storeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function addStoreRelationToProductAbstracts(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $idStores = $this->getIdStores();

        $productAbstractTransfer->setStoreRelation((new StoreRelationTransfer())->setIdStores($idStores));

        $this->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }
}
