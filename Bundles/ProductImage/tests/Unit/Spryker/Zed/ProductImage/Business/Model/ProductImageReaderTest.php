<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductImage\Business\Model;

use Codeception\TestCase\Test;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGenerator;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Model
 * @group ProductImageReaderTest
 */
class ProductImageReaderTest extends Test
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
     * @var \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGeneratorInterface
     */
    protected $transferGenerator;

    protected function setUp()
    {
        $this->queryContainer = new ProductImageQueryContainer();
        $this->localeFacade = new LocaleFacade();
        $this->transferGenerator = new ProductImageTransferGenerator(
            $this->localeFacade
        );

        $this->reader = new Reader(
            $this->queryContainer,
            $this->transferGenerator
        );
    }

    public function testGetProductImagesByProductAbstractId()
    {
        $imageCollection = $this->reader
            ->getProductImagesSetCollectionByProductAbstractId(1);

        $this->assertNotEmpty($imageCollection);
    }

}
