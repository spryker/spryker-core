<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductCategoryStorage;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductCategoryStorageBuilder;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClient;
use Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Client\StorageRedis\Plugin\StorageRedisPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductCategoryStorage
 * @group ProductCategoryStorageClientTest
 * Add your own group annotations below this line
 */
class ProductCategoryStorageClientTest extends Unit
{
    /**
     * @var int
     */
    protected const INVALID_ID_PRODUCT_ABSTRACT = 1234567890;

    /**
     * @var string
     */
    protected const HTTP_REFERER_CATEGORY_PAGE = 'http://yves.de.spryker.local/en/computer/notebook';

    /**
     * @var string
     */
    protected const HTTP_REFERER_MAIN_PAGE = 'http://yves.de.spryker.local/en';

    /**
     * @var string
     */
    protected const CATEGORY_URL_CAMERA = '/en/camera';

    /**
     * @var string
     */
    protected const CATEGORY_URL_CAMERA_DIGITAL = '/en/camera/digital';

    /**
     * @var string
     */
    protected const CATEGORY_URL_COMPUTER = '/en/computer';

    /**
     * @var string
     */
    protected const CATEGORY_URL_COMPUTER_NOTEBOOK = '/en/computer/notebook';

    /**
     * @var int
     */
    protected const CATEGORY_ID_ROOT = 1;

    /**
     * @var int
     */
    protected const CATEGORY_ID_CAMERA = 2;

    /**
     * @var int
     */
    protected const CATEGORY_ID_CAMERA_DIGITAL = 4;

    /**
     * @var int
     */
    protected const CATEGORY_ID_COMPUTER = 5;

    /**
     * @var int
     */
    protected const CATEGORY_ID_COMPUTER_NOTEBOOK = 6;

    /**
     * @var \SprykerTest\Client\ProductCategoryStorage\ProductCategoryStorageClientTester
     */
    protected ProductCategoryStorageClientTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(StorageDependencyProvider::PLUGIN_STORAGE, new StorageRedisPlugin());
    }

    /**
     * @return void
     */
    public function testFindInvalidProductAbstractCategoryReturnsNull(): void
    {
        // Act
        $returnValue = $this->createProductCategoryStorageClient()
            ->findProductAbstractCategory(static::INVALID_ID_PRODUCT_ABSTRACT, 'de_DE', 'DE');

        // Assert
        $this->assertNull($returnValue);
    }

    /**
     * @return void
     */
    public function testFindBulkProductAbstractCategoryReturnsEmptyArray(): void
    {
        // Act
        $productAbstractCategoryStorageTransfers = $this->createProductCategoryStorageClient()
            ->findBulkProductAbstractCategory([static::INVALID_ID_PRODUCT_ABSTRACT], 'de_DE', 'DE');

        // Assert
        $this->assertCount(0, $productAbstractCategoryStorageTransfers);
    }

    /**
     * @return void
     */
    public function testFilterProductCategoriesByHttpRefererThrowsExceptionWhenUrlIsMissing(): void
    {
        // Arrange
        $productCategoryStorageTransfers = [
            (new ProductCategoryStorageBuilder([
                ProductCategoryStorageTransfer::URL => null,
                ProductCategoryStorageTransfer::CATEGORY_ID => static::CATEGORY_ID_CAMERA,
                ProductCategoryStorageTransfer::PARENT_CATEGORY_IDS => [],
            ]))->build(),
        ];

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->createProductCategoryStorageClient()->filterProductCategoriesByHttpReferer(
            $productCategoryStorageTransfers,
            static::HTTP_REFERER_CATEGORY_PAGE,
        );
    }

    /**
     * @dataProvider productCategoryStorageTransfersForFilteringDataProvider
     *
     * @param string $httpReferer
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $expectedProductCategoryStorageTransfers
     *
     * @return void
     */
    public function testFilterProductCategoriesByProductCategoryStorageClientReturnsEqualArray(
        string $httpReferer,
        array $productCategoryStorageTransfers,
        array $expectedProductCategoryStorageTransfers
    ): void {
        // Act
        $productCategoryStorageTransfers = $this->createProductCategoryStorageClient()->filterProductCategoriesByHttpReferer(
            $productCategoryStorageTransfers,
            $httpReferer,
        );

        // Assert
        $this->assertEquals($productCategoryStorageTransfers, $expectedProductCategoryStorageTransfers);
    }

    /**
     * @dataProvider productCategoryStorageTransfersForSortingDataProvider
     *
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $expectedProductCategoryStorageTransfers
     *
     * @return void
     */
    public function testSortProductCategories(array $productCategoryStorageTransfers, array $expectedProductCategoryStorageTransfers): void
    {
        // Act
        $productCategoryStorageTransfers = $this->createProductCategoryStorageClient()->sortProductCategories(
            $productCategoryStorageTransfers,
        );

        // Assert
        foreach ($productCategoryStorageTransfers as $sortedProductCategoryStorageTransferKey => $sortedProductCategoryStorageTransfer) {
            $this->assertSame(
                $expectedProductCategoryStorageTransfers[$sortedProductCategoryStorageTransferKey]->getCategoryId(),
                $sortedProductCategoryStorageTransfer->getCategoryId(),
            );
        }
    }

    /**
     * @return array<array<string|list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>>>
     */
    protected function productCategoryStorageTransfersForFilteringDataProvider(): array
    {
        return [
            [
                static::HTTP_REFERER_CATEGORY_PAGE,
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA_DIGITAL,
                        static::CATEGORY_ID_CAMERA_DIGITAL,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER_NOTEBOOK,
                        static::CATEGORY_ID_COMPUTER_NOTEBOOK,
                        [static::CATEGORY_ID_COMPUTER],
                    ),
                ],
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER_NOTEBOOK,
                        static::CATEGORY_ID_COMPUTER_NOTEBOOK,
                        [static::CATEGORY_ID_COMPUTER],
                    ),
                ],
            ],
            [
                static::HTTP_REFERER_CATEGORY_PAGE,
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA_DIGITAL,
                        static::CATEGORY_ID_CAMERA_DIGITAL,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                ],
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                ],
            ],
            [
                static::HTTP_REFERER_CATEGORY_PAGE,
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA_DIGITAL,
                        static::CATEGORY_ID_CAMERA_DIGITAL,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                ],
                [
                ],
            ],
            [
                static::HTTP_REFERER_CATEGORY_PAGE,
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER_NOTEBOOK,
                        static::CATEGORY_ID_COMPUTER_NOTEBOOK,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                ],
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                ],
            ],
            [
                static::HTTP_REFERER_MAIN_PAGE,
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA_DIGITAL,
                        static::CATEGORY_ID_CAMERA_DIGITAL,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                ],
                [
                ],
            ],
        ];
    }

    /**
     * @return array<array<list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>>>
     */
    protected function productCategoryStorageTransfersForSortingDataProvider(): array
    {
        return [
            [
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER_NOTEBOOK,
                        static::CATEGORY_ID_COMPUTER_NOTEBOOK,
                        [static::CATEGORY_ID_COMPUTER],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA_DIGITAL,
                        static::CATEGORY_ID_CAMERA_DIGITAL,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                ],
                [
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA,
                        static::CATEGORY_ID_CAMERA,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_CAMERA_DIGITAL,
                        static::CATEGORY_ID_CAMERA_DIGITAL,
                        [static::CATEGORY_ID_CAMERA],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER,
                        static::CATEGORY_ID_COMPUTER,
                        [static::CATEGORY_ID_ROOT],
                    ),
                    $this->createProductCategoryStorageTransfer(
                        static::CATEGORY_URL_COMPUTER_NOTEBOOK,
                        static::CATEGORY_ID_COMPUTER_NOTEBOOK,
                        [static::CATEGORY_ID_COMPUTER],
                    ),
                ],
            ],
        ];
    }

    /**
     * @param string $url
     * @param int $categoryId
     * @param array<int> $parentCategoryIds
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer
     */
    protected function createProductCategoryStorageTransfer(string $url, int $categoryId, array $parentCategoryIds): ProductCategoryStorageTransfer
    {
        return (new ProductCategoryStorageBuilder([
            ProductCategoryStorageTransfer::URL => $url,
            ProductCategoryStorageTransfer::CATEGORY_ID => $categoryId,
            ProductCategoryStorageTransfer::PARENT_CATEGORY_IDS => $parentCategoryIds,
        ]))->build();
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface
     */
    protected function createProductCategoryStorageClient(): ProductCategoryStorageClientInterface
    {
        return new ProductCategoryStorageClient();
    }
}
