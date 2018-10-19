<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocale;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManager;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepository;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\CategoryImage\Business\Model\Writer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryImage
 * @group Business
 * @group Model
 * @group CategoryImageWriterTest
 * Add your own group annotations below this line
 */
class CategoryImageWriterTest extends Unit
{
    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Model\WriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
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
        $this->localeFacade = new LocaleFacade();
        $this->writer = new Writer(
            new CategoryImageRepository(),
            new CategoryImageEntityManager(),
            new CategoryImageToLocale($this->localeFacade)
        );
    }

    /**
     * @return void
     */
    public function testPersistCategoryImage()
    {
        $imageTransfer = new CategoryImageTransfer();
        $imageTransfer
            ->setSortOrder(11)
            ->setExternalUrlLarge('large')
            ->setExternalUrlSmall('small');

        $imageTransfer = $this->writer
            ->saveCategoryImage($imageTransfer);

        $this->assertInstanceOf(CategoryImageTransfer::class, $imageTransfer);
    }

    /**
     * @return void
     */
    public function testPersistCategoryImageSet()
    {
        $imageTransfer = new CategoryImageTransfer();
        $imageTransfer
            ->setSortOrder(7)
            ->setExternalUrlLarge('large')
            ->setExternalUrlSmall('small');

        $imageSetTransfer = new CategoryImageSetTransfer();
        $imageSetTransfer
            ->setIdCategory(1)
            ->setName('Foo')
            ->addCategoryImage($imageTransfer);

        $imageSetTransfer = $this->writer
            ->saveCategoryImageSet($imageSetTransfer);

        $this->assertInstanceOf(CategoryImageSetTransfer::class, $imageSetTransfer);
        $this->assertInstanceOf(CategoryImageTransfer::class, $imageSetTransfer->getCategoryImages()[0]);
    }
}
