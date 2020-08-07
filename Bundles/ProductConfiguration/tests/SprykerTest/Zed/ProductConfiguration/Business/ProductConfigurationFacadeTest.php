<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group Facade
 * @group ProductConfigurationFacadeTest
 * Add your own group annotations below this line
 */
class ProductConfigurationFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfiguration\ProductConfigurationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductConfigurationCollection(): void
    {
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ]
        );

        $productConfigurationCriteriaTransfer = (new ProductConfigurationFilterTransfer())
            ->setProductConfigurationIds([$productConfigurationTransfer->getIdProductConfiguration()]);

        $productConfigurationCollectionTransfer = $this->tester->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\ProductConfigurationTransfer $createdProductConfigurationTransfer */
        $createdProductConfigurationTransfer = $productConfigurationCollectionTransfer->getProductConfigurations()
            ->getIterator()->current();

        $this->assertNotEmpty($productConfigurationCollectionTransfer->getProductConfigurations());
        $this->assertEquals($productTransfer->getIdProductConcrete(), $createdProductConfigurationTransfer->getFkProduct());
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationCollectionWrongProductFK(): void
    {
        $productConfigurationCriteriaTransfer = (new ProductConfigurationFilterTransfer())
            ->setProductConfigurationIds([ProductConfigurationTransfer::FK_PRODUCT => 222]);

        $productConfigurationCollectionTransfer = $this->tester->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        $this->assertEmpty($productConfigurationCollectionTransfer->getProductConfigurations());
    }
}
