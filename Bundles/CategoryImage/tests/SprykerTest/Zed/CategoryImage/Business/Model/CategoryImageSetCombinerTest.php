<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CategoryImage\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryImage\Business\Model\CategoryImageSetCombiner;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryImage
 * @group Business
 * @group Model
 * @group CategoryImageSetCombinerTest
 * Add your own group annotations below this line
 */
class CategoryImageSetCombinerTest extends Unit
{
    public const CATEGORY_IMAGE_SET_NAME = 'test-category-image-set';
    public const CATEGORY_IMAGE_URL_SMALL = 'url-small';
    public const CATEGORY_IMAGE_URL_LARGE = 'url-large';

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Model\CategoryImageSetCombinerInterface;
     */
    private $categoryImageSetCombiner;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->repository = $this->createRepositoryMock();
        $this->categoryImageSetCombiner = new CategoryImageSetCombiner(
            $this->repository
        );
    }

    /**
     * @param string $categoryImageSetName1
     * @param string $categoryImageSetName2
     * @param int $expectedCount
     *
     * @dataProvider getCombinedCategoryImageSetDataProvider
     */
    public function testGetCombinedCategoryImageSets(
        string $categoryImageSetName1,
        string $categoryImageSetName2,
        int $expectedCount
    ) {
        $categoryImageTransfer = $this->createCategoryImageTransfer();
        $categoryImageSetTransfer1 = $this->createCategoryImageSetTransfer(
            $categoryImageTransfer,
            $categoryImageSetName1
        );
        $categoryImageSetTransfer2 = $this->createCategoryImageSetTransfer(
            $categoryImageTransfer,
            $categoryImageSetName2
        );
        $this->repository
            ->method('findDefaultCategoryImageSets')
            ->willReturn([$categoryImageSetTransfer1]);
        $this->repository
            ->method('findLocalizedCategoryImageSets')
            ->willReturn([$categoryImageSetTransfer2]);

        $result = $this->categoryImageSetCombiner->getCombinedCategoryImageSets(1, 1);
        $this->assertTrue(gettype($result) === 'array');
        $this->assertEquals($expectedCount, count($result));
    }

    /**
     * @return array
     */
    public function getCombinedCategoryImageSetDataProvider(): array
    {
        return [
            'Same name for default and localized image sets' => [
                static::CATEGORY_IMAGE_SET_NAME,
                static::CATEGORY_IMAGE_SET_NAME,
                1,
            ],
            'Different names for default and localized image sets' => [
                static::CATEGORY_IMAGE_SET_NAME . '1',
                static::CATEGORY_IMAGE_SET_NAME . '2',
                2,
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected function createRepositoryMock()
    {
        $repository = $this->getMockBuilder(CategoryImageRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     * @param string $categoryImageSetName
     * @param int $idCategoryImageSet
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function createCategoryImageSetTransfer(
        CategoryImageTransfer $categoryImageTransfer,
        string $categoryImageSetName = self::CATEGORY_IMAGE_SET_NAME,
        int $idCategoryImageSet = 1,
        int $idCategory = 1,
        LocaleTransfer $localeTransfer = null
    ): CategoryImageSetTransfer {
        $categoryImageSetTransfer = new CategoryImageSetTransfer();
        $categoryImageSetTransfer->setIdCategoryImageSet($idCategoryImageSet)
            ->setName($categoryImageSetName)
            ->setIdCategory($idCategory)
            ->addCategoryImage($categoryImageTransfer)
            ->setLocale($localeTransfer);

        return $categoryImageSetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    protected function createCategoryImageTransfer(): CategoryImageTransfer
    {
        $categoryImageTransfer = new CategoryImageTransfer();
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
