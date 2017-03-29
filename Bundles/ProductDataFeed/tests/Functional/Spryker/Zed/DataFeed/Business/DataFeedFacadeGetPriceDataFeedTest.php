<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\DataFeed\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedPaginationTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceFeedJoinTransfer;
use Spryker\Zed\DataFeed\Business\DataFeedFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group DataFeed
 * @group Business
 * @group DataFeedFacadeGetPriceDataFeedTest
 */
class DataFeedFacadeGetPriceDataFeedTest extends Test
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
     * @var PriceFeedJoinTransfer
     */
    protected $priceFeedJoinTransfer;

    /**
     * @var DataFeedPaginationTransfer
     */
    protected $dataFeedPaginationTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->dataFeedFacade = $this->createDataFeedFacade();
        $this->dataFeedTransfer = $this->createDataFeedTransfer();
        $this->priceFeedJoinTransfer = $this->createPriceFeedJoinTransfer();
        $this->dataFeedPaginationTransfer = $this->createDataFeedPaginationTransfer();
    }

    public function testGetPriceDataFeed()
    {
        $this->dataFeedTransfer->setPriceFeedJoin($this->priceFeedJoinTransfer);

        $result = $this->dataFeedFacade->getPriceDataFeed($this->dataFeedTransfer);

        $this->assertCount(151, $result);
        $this->assertEquals($this->getPriceKeys(), array_keys($result[0]));
    }

    public function testGetPriceWithJoinedPriceTypesDataFeed()
    {
        $this->priceFeedJoinTransfer->setIsJoinType(true);
        $this->dataFeedTransfer->setPriceFeedJoin($this->priceFeedJoinTransfer);

        $result = $this->dataFeedFacade->getPriceDataFeed($this->dataFeedTransfer);

        $this->assertCount(151, $result);

        $expectedKeys = array_merge(
            $this->getPriceKeys(),
            $this->getPriceTypeKeys()
        );
        $this->assertEquals($expectedKeys, array_keys($result[0]));
    }

    public function testGetPriceWithPaginationDataFeed()
    {
        $this->dataFeedPaginationTransfer->setLimit(30);
        $this->dataFeedTransfer->setPagination($this->dataFeedPaginationTransfer);
        $this->dataFeedTransfer->setPriceFeedJoin($this->priceFeedJoinTransfer);

        $result = $this->dataFeedFacade->getPriceDataFeed($this->dataFeedTransfer);

        $this->assertCount(30, $result);
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
     * @return PriceFeedJoinTransfer
     */
    protected function createPriceFeedJoinTransfer()
    {
        $priceFeedJoinTransfer = new PriceFeedJoinTransfer();

        return $priceFeedJoinTransfer;
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
     * @return array
     */
    protected function getPriceKeys()
    {
        return [
            'IdPriceProduct',
            'Price',
            'FkProduct',
            'FkPriceType',
            'FkProductAbstract',
        ];
    }

    /**
     * @return array
     */
    protected function getPriceTypeKeys()
    {
        return [
            'SpyPriceTypeIdPriceType',
            'SpyPriceTypeName',
        ];
    }

}