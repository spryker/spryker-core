<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductDataFeedTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\ProductDataFeed\Persistence\ProductDataFeedQueryContainer;

class ProductDataFeedQueryContainerTest extends Test
{

    /**
     * @var ProductDataFeedQueryContainer
     */
    protected $productDataFeedQueryContainer;

    /**
     * @var ProductDataFeedTransfer
     */
    protected $productDataFeedTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->productDataFeedQueryContainer = $this->createProductDataFeedQueryContainer();
        $this->productDataFeedTransfer = $this->createProductDataFeedTransfer();
    }


    public function testGetProductDataFeedQuery()
    {
        $query = $this->productDataFeedQueryContainer
            ->getProductDataFeedQuery($this->productDataFeedTransfer);

        var_dump($query); die;

        $this->assertTrue(true);
    }

    /**
     * @return ProductDataFeedQueryContainer
     */
    protected function createProductDataFeedQueryContainer()
    {
        $productQueryContainer = new ProductQueryContainer();
        $productDataFeedQueryContainer = new ProductDataFeedQueryContainer($productQueryContainer);
        
        return $productDataFeedQueryContainer;
    }

    /**
     * @return ProductDataFeedTransfer
     */
    protected function createProductDataFeedTransfer()
    {
        $productDataFeedTransfer = new ProductDataFeedTransfer();

        return $productDataFeedTransfer;
    }


}