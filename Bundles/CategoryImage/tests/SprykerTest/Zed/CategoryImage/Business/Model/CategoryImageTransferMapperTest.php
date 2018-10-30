<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapper;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleBridge;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepository;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\CategoryImage\Business\Model\Reader;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Model
 * @group CategoryImageTransferMapperTest
 * Add your own group annotations below this line
 */
class CategoryImageTransferMapperTest extends Unit
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface
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
            new CategoryImageToLocaleBridge($this->localeFacade)
        );

        $this->reader = new Reader(
            $this->repository,
            $this->transferGenerator,
            new CategoryImageToLocaleBridge($this->localeFacade)
        );
    }

    /**
     * @return void
     */
    public function testConvertProductImageSetEntitiesIntoTransfer()
    {
        $transferCollection = $this->reader
            ->findCategoryImagesSetCollectionByCategoryId(1);

        foreach ($transferCollection as $transfer) {
            $this->assertInstanceOf(CategoryImageSetTransfer::class, $transfer);

            $this->assertNotEmpty(
                $transfer->getCategoryImages()
            );
        }
    }
}
