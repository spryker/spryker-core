<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

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
 * @method void pause()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\CategoryStorage\PHPMD)
 */
class CategoryStorageBusinessTester extends Actor
{
    use _generated\CategoryStorageBusinessTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->setDependency(StoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
    }

    /**
     * @return void
     */
    public function ensureCategoryTreeStorageDatabaseTableIsEmpty(): void
    {
        SpyCategoryTreeStorageQuery::create()->deleteAll();
    }

    /**
     * @param array $categoryData
     * @param array $storeData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveLocalizedCategoryWithStoreRelation(array $categoryData = [], array $storeData = []): CategoryTransfer
    {
        $categoryTransfer = $this->haveLocalizedCategory($categoryData);
        $storeTransfer = $this->haveStore($storeData);
        $this->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage|null
     */
    public function findCategoryNodeStorageEntityByLocalizedCategoryAndStoreName(CategoryTransfer $categoryTransfer, string $storeName): ?SpyCategoryNodeStorage
    {
        return $this->createSpyCategoryNodeStorageQueryByLocalizedCategoryAndStoreName($categoryTransfer, $storeName)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage>
     */
    public function findCategoryTreeStorageEntitiesByLocalizedCategoryAndStoreName(CategoryTransfer $categoryTransfer, string $storeName): ObjectCollection
    {
        return $this->createSpyCategoryTreeStorageQueryByLocalizedCategoryAndStoreName($categoryTransfer, $storeName)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     * @param array $storageData
     *
     * @return void
     */
    public function haveCategoryNodeStorageByLocalizedCategory(
        CategoryTransfer $categoryTransfer,
        string $storeName,
        array $storageData = []
    ): void {
        $spyCategoryNodeStorageEntity = $this->createSpyCategoryNodeStorageQueryByLocalizedCategoryAndStoreName($categoryTransfer, $storeName)
            ->findOneOrCreate();
        if (!$spyCategoryNodeStorageEntity->isNew()) {
            return;
        }

        $spyCategoryNodeStorageEntity->setData(
            $this->getLocator()->utilEncoding()->service()->encodeJson($storageData),
        );

        $spyCategoryNodeStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage
     */
    public function haveCategoryTreeStorageEntityByLocalizedCategoryAndStoreName(
        CategoryTransfer $categoryTransfer,
        string $storeName
    ): SpyCategoryTreeStorage {
        $categoryTreeStorageEntity = $this->createSpyCategoryTreeStorageQueryByLocalizedCategoryAndStoreName(
            $categoryTransfer,
            $storeName,
        )->findOneOrCreate();

        if ($categoryTreeStorageEntity->isNew()) {
            $categoryTreeStorageEntity->save();
        }

        return $categoryTreeStorageEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery
     */
    protected function createSpyCategoryNodeStorageQueryByLocalizedCategoryAndStoreName(
        CategoryTransfer $categoryTransfer,
        string $storeName
    ): SpyCategoryNodeStorageQuery {
        return SpyCategoryNodeStorageQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByStore($storeName)
            ->filterByLocale($this->extractLocaleNameFromLocalizedCategory($categoryTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery
     */
    protected function createSpyCategoryTreeStorageQueryByLocalizedCategoryAndStoreName(
        CategoryTransfer $categoryTransfer,
        string $storeName
    ): SpyCategoryTreeStorageQuery {
        return SpyCategoryTreeStorageQuery::create()
            ->filterByStore($storeName)
            ->filterByLocale($this->extractLocaleNameFromLocalizedCategory($categoryTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return string
     */
    protected function extractLocaleNameFromLocalizedCategory(CategoryTransfer $categoryTransfer): string
    {
        return $categoryTransfer->getLocalizedAttributes()
            ->offsetGet(0)
            ->getLocale()
            ->getLocaleName();
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
    }
}
