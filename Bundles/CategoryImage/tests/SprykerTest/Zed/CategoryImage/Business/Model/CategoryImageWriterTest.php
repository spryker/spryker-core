<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManager;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepository;
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

    public const CATEGORY_KEY = 'test-category';
    public const CATEGORY_IMAGE_SET_NAME = 'test-category-image-set';
    public const CATEGORY_IMAGE_URL_SMALL = 'url-small';
    public const CATEGORY_IMAGE_URL_LARGE = 'url-large';

    public const CATEGORY_ID_1 = 1;
    public const CATEGORY_ID_2 = 2;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $repository;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Model\WriterInterface
     */
    protected $writer;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->repository = $this->createRepositoryMock();
        $this->entityManager = $this->createEntityManagerMock();
        $this->writer = new Writer(
            $this->repository,
            $this->entityManager
        );
    }

    public function testSaveCategoryImageSet()
    {
        $missingCategoryImage = $this->createCategoryImageTransfer(1);
        $categoryImage = $this->createCategoryImageTransfer(2);
        $categoryImageSet = $this->createCategoryImageSetTransfer(3, 4, $categoryImage);

        $this->repository
            ->expects($this->once())
            ->method('findCategoryImagesByCategoryImageSetId')
            ->willReturn([
                $missingCategoryImage,
            ]);
        $this->entityManager
            ->expects($this->once())
            ->method('deleteCategoryImageSetToCategoryImage')
            ->with($this->equalTo(
                $categoryImageSet->getIdCategoryImageSet(),
                $missingCategoryImage->getIdCategoryImage()
            ));
        $this->entityManager
            ->method('saveCategoryImageSet')
            ->willReturn($categoryImageSet);
        $this->entityManager
            ->method('saveCategoryImage')
            ->willReturn($categoryImage);

        $this->writer->saveCategoryImageSet($categoryImageSet);
    }

    public function testCreateCategoryImageSetCollection()
    {
        $categoryImageTransfer = $this->createCategoryImageTransfer(static::CATEGORY_ID_1);
        $categoryImageSetTransfer = $this->createCategoryImageSetTransfer(1, static::CATEGORY_ID_2, $categoryImageTransfer);
        $categoryTransfer = $this->createCategoryTransfer(11);
        $categoryTransfer->addImageSet($categoryImageSetTransfer);

        $this->repository
            ->expects($this->once())
            ->method('findCategoryImagesByCategoryImageSetId')
            ->willReturn([]);
        $this->entityManager
            ->expects($this->never())
            ->method('deleteCategoryImageSetToCategoryImage');
        $this->entityManager
            ->method('saveCategoryImageSet')
            ->willReturn($categoryImageSetTransfer);
        $this->entityManager
            ->method('saveCategoryImage')
            ->willReturn($categoryImageTransfer);

        $this->assertNotEquals($categoryTransfer->getIdCategory(), $categoryImageSetTransfer->getIdCategory());
        $this->writer->createCategoryImageSetCollection($categoryTransfer);
        $this->assertEquals($categoryTransfer->getIdCategory(), $categoryImageSetTransfer->getIdCategory());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected function createRepositoryMock()
    {
        return $this->getMockBuilder(CategoryImageRepository::class)
            ->setMethodsExcept([])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface
     */
    protected function createEntityManagerMock(): CategoryImageEntityManagerInterface
    {
        return $this->getMockBuilder(CategoryImageEntityManager::class)
            ->setMethodsExcept([])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createCategoryTransfer(int $idCategory): CategoryTransfer
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setCategoryKey(static::CATEGORY_KEY);
        $categoryTransfer->setIdCategory($idCategory);
        $categoryTransfer->setIsActive(true);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategory
     * @param CategoryImageTransfer|null $categoryImageTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function createCategoryImageSetTransfer(
        int $idCategoryImageSet,
        int $idCategory,
        CategoryImageTransfer $categoryImageTransfer = null,
        LocaleTransfer $localeTransfer = null
    ): CategoryImageSetTransfer {
        if (!$categoryImageTransfer) {
            $categoryImageTransfer = $this->createCategoryImageTransfer();
        }

        $categoryImageSetTransfer = new CategoryImageSetTransfer();
        $categoryImageSetTransfer->setIdCategoryImageSet($idCategoryImageSet)
            ->setName(static::CATEGORY_IMAGE_SET_NAME)
            ->setIdCategory($idCategory)
            ->addCategoryImage($categoryImageTransfer)
            ->setLocale($localeTransfer);

        return $categoryImageSetTransfer;
    }

    /**
     * @param int $idCategoryImage
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    protected function createCategoryImageTransfer(int $idCategoryImage = 1): CategoryImageTransfer
    {
        $categoryImageTransfer = new CategoryImageTransfer();
        $categoryImageTransfer->setIdCategoryImage($idCategoryImage);
        $categoryImageTransfer->setExternalUrlSmall(static::CATEGORY_IMAGE_URL_SMALL)
            ->setExternalUrlLarge(static::CATEGORY_IMAGE_URL_LARGE);

        return $categoryImageTransfer;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer(string $localeName = 'default'): LocaleTransfer
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        return $localeTransfer;
    }
}
