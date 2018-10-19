<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapper;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocale;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepository;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\CategoryImage\Business\Model\Reader;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryImage
 * @group Business
 * @group Model
 * @group CategoryImageReaderTest
 * Add your own group annotations below this line
 */
class CategoryImageReaderTest extends Unit
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface
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
        $this->repository = new CategoryImageRepository();
        $this->localeFacade = new LocaleFacade();
        $this->transferGenerator = new CategoryImageTransferMapper(
            new CategoryImageToLocale($this->localeFacade)
        );

        $this->reader = new Reader(
            $this->repository,
            $this->transferGenerator,
            $this->localeFacade
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
