<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery;
use Spryker\Client\Store\StoreDependencyProvider as ClientStoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

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
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CategoryPageSearchBusinessTester extends Actor
{
    use _generated\CategoryPageSearchBusinessTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var string
     */
    protected const LOCALE_NAME_EN = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(ClientStoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
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

        $storeData += [
            StoreTransfer::NAME => static::DEFAULT_STORE_NAME,
            StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [static::LOCALE_NAME_DE, static::LOCALE_NAME_EN],
        ];
        $storeTransfer = $this->haveStore($storeData);
        $this->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch|null
     */
    public function findCategoryNodePageSearchEntityByLocalizedCategory(CategoryTransfer $categoryTransfer): ?SpyCategoryNodePageSearch
    {
        return $this->createSpyCategoryQueryByLocalizedCategory($categoryTransfer)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array $searchData
     *
     * @return void
     */
    public function haveCategoryNodePageSearchByLocalizedCategory(
        CategoryTransfer $categoryTransfer,
        array $searchData = []
    ): void {
        $spyCategoryNodePageSearchEntity = $this->createSpyCategoryQueryByLocalizedCategory($categoryTransfer)->findOneOrCreate();
        if (!$spyCategoryNodePageSearchEntity->isNew()) {
            return;
        }

        $encodedSearchData = $this->getUtilEncodingService()->encodeJson($searchData);

        $spyCategoryNodePageSearchEntity->setStore(static::DEFAULT_STORE_NAME);
        $spyCategoryNodePageSearchEntity->setStructuredData($encodedSearchData);
        $spyCategoryNodePageSearchEntity->setData($encodedSearchData);
        $spyCategoryNodePageSearchEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery
     */
    protected function createSpyCategoryQueryByLocalizedCategory(CategoryTransfer $categoryTransfer): SpyCategoryNodePageSearchQuery
    {
        $localeName = $categoryTransfer->getLocalizedAttributes()
            ->offsetGet(0)
            ->getLocale()
            ->getLocaleName();

        return SpyCategoryNodePageSearchQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByLocale($localeName);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getLocator()->utilEncoding()->service();
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE_NAME)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
    }
}
