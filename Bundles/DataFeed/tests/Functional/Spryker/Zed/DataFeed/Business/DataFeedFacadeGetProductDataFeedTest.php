<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\DataFeed\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedDateFilterTransfer;
use Generated\Shared\Transfer\DataFeedPaginationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductFeedJoinTransfer;
use Spryker\Zed\DataFeed\Business\DataFeedFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group DataFeed
 * @group Business
 * @group DataFeedFacadeGetProductDataFeedTest
 */
class DataFeedFacadeGetProductDataFeedTest extends Test
{

    /**
     * @var DataFeedConditionTransfer
     */
    protected $dataFeedTransfer;

    /**
     * @var LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var DataFeedFacade
     */
    protected $dataFeedFacade;

    /**
     * @var DataFeedPaginationTransfer
     */
    protected $dataFeedPaginationTransfer;

    /**
     * @var DataFeedDateFilterTransfer
     */
    protected $dataFeedDateFilterTransfer;

    /**
     * @var ProductFeedJoinTransfer
     */
    protected $productFeedJoinTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->dataFeedFacade = $this->createDataFeedFacade();
        $this->localeTransfer = $this->createLocaleTransfer();
        $this->dataFeedTransfer = $this->createDataFeedTransfer();
        $this->dataFeedPaginationTransfer = $this->createDataFeedPaginationTransfer();
        $this->dataFeedDateFilterTransfer = $this->createDataFeedDateFilterTransfer();
        $this->productFeedJoinTransfer = $this->createProductFeedJoinTransfer();
    }

    public function testLocaleNameConditionGetProductFeed()
    {
        $this->localeTransfer->setLocaleName('de_DE');
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
    }

    public function testLocaleIdConditionGetProductFeed()
    {
        $this->localeTransfer->setIdLocale(46);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
    }

    public function testLocaleIsActiveConditionGetProductFeed()
    {
        $this->localeTransfer->setIsActive(true);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
    }

    public function testLocaleIsNotActiveConditionGetProductFeed()
    {
        $this->localeTransfer->setIsActive(false);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(0, $result);
    }

    public function testPaginationGetProductFeed()
    {
        $this->dataFeedPaginationTransfer->setLimit(20);
//        $this->dataFeedPaginationTransfer->setOffset(0);
        $this->dataFeedTransfer->setPagination($this->dataFeedPaginationTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(20, $result);
    }

    public function testDateFilterGetProductFeed()
    {
        $this->dataFeedDateFilterTransfer->setUpdatedFrom('2017-03-20');
        $this->dataFeedDateFilterTransfer->setUpdatedTo('2017-03-22');
        $this->dataFeedTransfer->setDateFilter($this->dataFeedDateFilterTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);

        $this->dataFeedDateFilterTransfer->setUpdatedFrom('2017-03-22');
        $this->dataFeedDateFilterTransfer->setUpdatedTo(null);
        $this->dataFeedTransfer->setDateFilter($this->dataFeedDateFilterTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(0, $result);
    }

    public function testJoinImageGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinImage(true);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
        $this->assertTrue(isset($result[0]['SpyProductImageSets'][0]['SpyProductImageSetToProductImages'][0]['SpyProductImage']));
    }

    public function testJoinImageFilteringExistingLocaleGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinImage(true);
        $this->localeTransfer->setIdLocale(46);

        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
        $this->assertTrue(isset($result[0]['SpyProductImageSets'][0]['SpyProductImageSetToProductImages'][0]['SpyProductImage']));
    }

    public function testJoinImageFilteringNotExistingLocaleGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinImage(true);
        $this->localeTransfer->setIdLocale(999);

        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(0, $result);
    }

    public function testJoinProductCategoryGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinCategory(true);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
        $this->assertTrue(isset($result[0]['SpyProductCategories'][0]['SpyCategory']['Attributes']));
    }

    public function testJoinProductPriceGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinPrice(true);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
        $this->assertTrue(isset($result[0]['PriceProducts'][0]['PriceType']));
    }

    public function testJoinProductCategoryFilteringLocaleGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinCategory(true);
        $this->localeTransfer->setIdLocale(46);

        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
    }

    public function testJoinVariantGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinVariant(true);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
        $this->assertTrue(isset($result[0]['SpyProducts']));
    }

    public function testJoinVariantFilterLocaleGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinVariant(true);
        $this->localeTransfer->setIdLocale(46);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);
        $this->dataFeedTransfer->setLocale($this->localeTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
    }

    public function testJoinOptionGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinOption(true);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);
        $this->assertTrue(isset($result[0]['SpyProductAbstractProductOptionGroups'][0]['SpyProductOptionGroup']['SpyProductOptionValues']));
    }

    public function testAllJoinsGetProductFeed()
    {
        $this->productFeedJoinTransfer->setIsJoinOption(true);
        $this->productFeedJoinTransfer->setIsJoinImage(true);
        $this->productFeedJoinTransfer->setIsJoinPrice(true);
        $this->productFeedJoinTransfer->setIsJoinVariant(true);
        $this->productFeedJoinTransfer->setIsJoinCategory(true);
        $this->dataFeedTransfer->setProductFeedJoin($this->productFeedJoinTransfer);

        $result = $this->dataFeedFacade->getProductDataFeed($this->dataFeedTransfer);

        $this->assertCount(133, $result);

        $this->assertTrue(isset($result[0]['PriceProducts']));
        $this->assertTrue(isset($result[0]['SpyProductAbstractLocalizedAttributess']));
        $this->assertTrue(isset($result[0]['SpyProducts']));
        $this->assertTrue(isset($result[0]['SpyProductCategories']));
        $this->assertTrue(isset($result[0]['SpyProductImageSets']));
        $this->assertTrue(isset($result[0]['SpyProductAbstractProductOptionGroups']));
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
     * @return LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $localeTransfer = new LocaleTransfer();

        return $localeTransfer;
    }

    /**
     * @return DataFeedPaginationTransfer
     */
    protected function createDataFeedPaginationTransfer()
    {
        $paginationTransfer = new DataFeedPaginationTransfer();

        return $paginationTransfer;
    }

    /**
     * @return DataFeedDateFilterTransfer
     */
    protected function createDataFeedDateFilterTransfer()
    {
        $dataFeedDateFilterTransfer = new DataFeedDateFilterTransfer();

        return $dataFeedDateFilterTransfer;
    }

    /**
     * @return ProductFeedJoinTransfer
     */
    protected function createProductFeedJoinTransfer()
    {
        $productFeedJoinTransfer = new ProductFeedJoinTransfer();
        
        return $productFeedJoinTransfer;
    }

}