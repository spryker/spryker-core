<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\DataFeed\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryFeedJoinTransfer;
use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedPaginationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceFeedJoinTransfer;
use Generated\Shared\Transfer\StockFeedJoinTransfer;
use Spryker\Zed\DataFeed\Business\DataFeedFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group DataFeed
 * @group Business
 * @group DataFeedFacadeGetPriceDataFeedTest
 */
class DataFeedFacadeGetStockDataFeedTest extends Test
{

    /**
     * @var DataFeedConditionTransfer
     */
    protected $dataFeedTransfer;

    /**
     * @var DataFeedFacade
     */
    protected $dataFeedFacade;

    /**
     * @var CategoryFeedJoinTransfer
     */
    protected $stockFeedJoinTransfer;

    /**
     * @var LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var DataFeedPaginationTransfer
     */
    protected $dataFeedPaginationTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->dataFeedFacade = $this->createDataFeedFacade();
        $this->dataFeedTransfer = $this->createDataFeedTransfer();
        $this->stockFeedJoinTransfer = $this->createStockFeedJoinTransfer();
        $this->dataFeedPaginationTransfer = $this->createDataFeedPaginationTransfer();
        $this->localeTransfer = $this->createLocaleTransfer();
    }

    public function testGetCategoryDataFeed()
    {
        $this->localeTransfer->setIdLocale(46);
        $this->dataFeedTransfer->setCategoryFeedJoin($this->stockFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getCategoryDataFeed($this->dataFeedTransfer);

        $this->assertCount(13, $result);
        $this->assertEquals($this->getCategoryKeys(), array_keys($result[0]));
    }

    public function testWithNotExistingLocaleGetCategoryDataFeed()
    {
        $this->localeTransfer->setIdLocale(999);
        $this->dataFeedTransfer->setCategoryFeedJoin($this->stockFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getCategoryDataFeed($this->dataFeedTransfer);

        $this->assertCount(0, $result);
    }

    public function testPaginationGetCategoryDataFeed()
    {
        $this->localeTransfer->setIdLocale(46);
        $this->dataFeedPaginationTransfer->setLimit(5);
        $this->dataFeedTransfer->setCategoryFeedJoin($this->stockFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);
        $this->dataFeedTransfer->setPagination($this->dataFeedPaginationTransfer);

        $result = $this->dataFeedFacade->getCategoryDataFeed($this->dataFeedTransfer);

        $this->assertCount(5, $result);
    }

    public function testJoinProductsGetCategoryDataFeed()
    {
        $this->localeTransfer->setIdLocale(46);
        $this->stockFeedJoinTransfer->setIsJoinProduct(true);
        $this->dataFeedTransfer->setCategoryFeedJoin($this->stockFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getCategoryDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);

        $expectedKeys = array_merge(
            $this->getCategoryKeys(),
            $this->getProductKeys()
        );
        $this->assertEquals($expectedKeys, array_keys($result[0]));
    }

    /**
     * @return DataFeedFacade
     */
    protected function createDataFeedFacade()
    {
        $dataFeedFacade = new DataFeedFacade();

        return $dataFeedFacade;
    }

    /**
     * @return DataFeedConditionTransfer
     */
    protected function createDataFeedTransfer()
    {
        $dataFeedTransfer = new DataFeedConditionTransfer();

        return $dataFeedTransfer;
    }

    /**
     * @return StockFeedJoinTransfer
     */
    protected function createStockFeedJoinTransfer()
    {
        $stockFeedJoinTransfer = new StockFeedJoinTransfer();

        return $stockFeedJoinTransfer;
    }

    /**
     * @return DataFeedPaginationTransfer
     */
    protected function createDataFeedPaginationTransfer()
    {
        $dataFeedPaginationTransfer = new DataFeedPaginationTransfer();

        return $dataFeedPaginationTransfer;
    }

    /**
     * @return LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $localeTransfer = new LocaleTransfer();

        return $localeTransfer;
    }

    /**
     * @return array
     */
    protected function getCategoryKeys()
    {
        return [
            'IdCategory',
            'CategoryKey',
            'IsActive',
            'IsInMenu',
            'IsClickable',
            'IsSearchable',
            'id_category',
            'name',
            'category_key',
            'id_category_node',
        ];
    }

    /**
     * @return array
     */
    protected function getProductKeys()
    {
        return [
            'SpyProductAbstractIdProductAbstract',
            'SpyProductAbstractSku',
            'SpyProductAbstractAttributes',
            'SpyProductAbstractFkTaxSet',
            'SpyProductAbstractLocalizedAttributesIdAbstractAttributes',
            'SpyProductAbstractLocalizedAttributesFkProductAbstract',
            'SpyProductAbstractLocalizedAttributesFkLocale',
            'SpyProductAbstractLocalizedAttributesName',
            'SpyProductAbstractLocalizedAttributesAttributes',
            'SpyProductAbstractLocalizedAttributesDescription',
            'SpyProductAbstractLocalizedAttributesMetaDescription',
            'SpyProductAbstractLocalizedAttributesMetaKeywords',
            'SpyProductAbstractLocalizedAttributesMetaTitle',
            'SpyProductAbstractLocalizedAttributesCreatedAt',
            'SpyProductAbstractLocalizedAttributesUpdatedAt',
        ];
    }

}