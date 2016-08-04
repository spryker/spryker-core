<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductImage\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGenerator;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;
use Codeception\TestCase\Test;

class ProductImageTransferGeneratorTest extends Test
{


    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGeneratorInterface
     */
    protected $transferGenerator;


    protected function setUp()
    {
        $this->queryContainer = new ProductImageQueryContainer();
        $this->localeFacade =  new LocaleFacade();
        $this->transferGenerator = new ProductImageTransferGenerator(
            $this->localeFacade
        );

        $this->reader = new Reader(
            $this->queryContainer,
            $this->transferGenerator
        );
    }

    public function testConvertProductImageSetEntitiesIntoTransfer()
    {
        $transferCollection = $this->reader
            ->getProductImagesSetByProductAbstractId(1);

        foreach ($transferCollection as $transfer) {
            $this->assertInstanceOf(ProductImageSetTransfer::class, $transfer);

            $this->assertNotEmpty(
                $transfer->getProductImages()
            );
        }
    }

}
