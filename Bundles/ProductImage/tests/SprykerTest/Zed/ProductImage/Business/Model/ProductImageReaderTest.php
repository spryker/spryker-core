<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapper;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleBridge;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Model
 * @group ProductImageReaderTest
 * Add your own group annotations below this line
 */
class ProductImageReaderTest extends Unit
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
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface
     */
    protected $transferGenerator;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->queryContainer = new ProductImageQueryContainer();
        $this->localeFacade = new LocaleFacade();
        $this->transferGenerator = new ProductImageTransferMapper(
            new ProductImageToLocaleBridge($this->localeFacade)
        );

        $this->reader = new Reader(
            $this->queryContainer,
            $this->transferGenerator,
            new ProductImageToLocaleBridge($this->localeFacade)
        );
    }

    /**
     * @return void
     */
    public function testGetProductImagesByProductAbstractId()
    {
        $imageCollection = $this->reader
            ->getProductImagesSetCollectionByProductAbstractId(1);

        $this->assertNotEmpty($imageCollection);
    }
}
