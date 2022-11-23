<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationCriteriaBuilder;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\ProductConfiguration\ProductConfigurationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group Facade
 * @group GetProductConfigurationCollectionTest
 * Add your own group annotations below this line
 */
class GetProductConfigurationCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const UUID_ONE = 'ebad5042-0db1-498e-9981-42f45f98ad3d';

    /**
     * @var string
     */
    protected const UUID_TWO = 'b7b94e0f-ec4d-4341-9132-07342b45f659';

    /**
     * @var string
     */
    protected const SKU = '9887571008';

    /**
     * @var \SprykerTest\Zed\ProductConfiguration\ProductConfigurationBusinessTester
     */
    protected ProductConfigurationBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetProductConfigurationReturnsEmptyCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->haveProductConfigurationTransferPersisted();

        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaBuilder())
            ->withProductConfigurationConditions([
                ProductConfigurationConditionsTransfer::UUIDS => [
                    static::UUID_ONE,
                ],
            ])
            ->build();

        // Act
        $productConfigurationCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        // Assert
        $this->assertProductConfigurationCollectionIsEmpty($productConfigurationCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationReturnsCollectionWithOneProductConfigurationWhileUuidCriteriaMatched(): void
    {
        // Arrange
        $productConfigurationTransfer = $this->tester->haveProductConfigurationTransferPersisted([
            ProductConfigurationTransfer::UUID => static::UUID_ONE,
        ]);

        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaBuilder())
            ->withProductConfigurationConditions([
                ProductConfigurationConditionsTransfer::UUIDS => [
                    $productConfigurationTransfer->getUuid(),
                ],
            ])
            ->build();

        // Act
        $productConfigurationCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        // Assert
        $this->assertProductConfigurationCollectionContainsTransferWithId($productConfigurationCollectionTransfer, $productConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationReturnsCollectionWithOneProductConfigurationWhileSkuCriteriaMatched(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => static::SKU,
        ]);

        $productConfigurationTransfer = $this->tester->haveProductConfigurationTransferPersisted([
            ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaBuilder())
            ->withProductConfigurationConditions([
                ProductConfigurationConditionsTransfer::SKUS => [
                    $productConcreteTransfer->getSku(),
                ],
            ])
            ->build();

        // Act
        $productConfigurationCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        // Assert
        $this->assertProductConfigurationCollectionContainsTransferWithId(
            $productConfigurationCollectionTransfer,
            $productConfigurationTransfer,
        );
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationReturnsCollectionWithOneProductConfigurationWhileIdProductConfigurationCriteriaMatched(): void
    {
        // Arrange
        $productConfigurationTransfer = $this->tester->haveProductConfigurationTransferPersisted();

        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaBuilder())
            ->withProductConfigurationConditions([
                ProductConfigurationConditionsTransfer::PRODUCT_CONFIGURATION_IDS => [
                    $productConfigurationTransfer->getIdProductConfiguration(),
                ],
            ])
            ->build();

        // Act
        $productConfigurationCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        // Assert
        $this->assertProductConfigurationCollectionContainsTransferWithId(
            $productConfigurationCollectionTransfer,
            $productConfigurationTransfer,
        );
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationReturnsCollectionWithFiveProductConfigurationsWhileHavingLimitOffsetPaginationAndSortingApplied(): void
    {
        // Arrange
        $persistedProductConfigurationCollectionTransfer = $this->tester
            ->haveProductConfigurationCollectionTransferWithProductConfigurationTransfersPersisted(15);

        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaBuilder())
            ->withPagination([
                PaginationTransfer::LIMIT => 5,
                PaginationTransfer::OFFSET => 5,
            ])
            ->withSort([
                SortTransfer::IS_ASCENDING => false,
                SortTransfer::FIELD => ProductConfigurationTransfer::ID_PRODUCT_CONFIGURATION,
            ])
            ->build();

        // Act
        $productConfigurationCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        // Assert
        $this->assertProductConfigurationCollectionIsPaginatedAndSorted(
            $productConfigurationCriteriaTransfer,
            $persistedProductConfigurationCollectionTransfer,
            $productConfigurationCollectionTransfer,
        );
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationReturnsCollectionWithFiveProductConfigurationsWhileHavingPagePaginationAndSortingApplied(): void
    {
        // Arrange
        $persistedProductConfigurationCollectionTransfer = $this->tester
            ->haveProductConfigurationCollectionTransferWithProductConfigurationTransfersPersisted(15);

        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaBuilder())
            ->withPagination([
                PaginationTransfer::PAGE => 2,
                PaginationTransfer::MAX_PER_PAGE => 5,
            ])
            ->withSort([
                SortTransfer::IS_ASCENDING => false,
                SortTransfer::FIELD => ProductConfigurationTransfer::ID_PRODUCT_CONFIGURATION,
            ])
            ->build();

        // Act
        $productConfigurationCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        // Assert
        $this->assertProductConfigurationCollectionIsPaginatedAndSorted(
            $productConfigurationCriteriaTransfer,
            $persistedProductConfigurationCollectionTransfer,
            $productConfigurationCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
     *
     * @return void
     */
    protected function assertProductConfigurationCollectionIsEmpty(ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer): void
    {
        $this->assertCount(
            0,
            $productConfigurationCollectionTransfer->getProductConfigurations(),
            sprintf('Expected to have an empty collection but found "%s" items', $productConfigurationCollectionTransfer->getProductConfigurations()->count()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     *
     * @return void
     */
    protected function assertProductConfigurationCollectionContainsTransferWithId(
        ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer,
        ProductConfigurationTransfer $productConfigurationTransfer
    ): void {
        $transferFound = false;

        foreach ($productConfigurationCollectionTransfer->getProductConfigurations() as $productConfigurationTransferFromCollection) {
            if ($productConfigurationTransferFromCollection->getIdProductConfiguration() === $productConfigurationTransfer->getIdProductConfiguration()) {
                $transferFound = true;
            }
        }

        $this->assertTrue(
            $transferFound,
            sprintf('Expected to have a transfer in the collection but transfer by id "%s" was not found in the collection', $productConfigurationTransfer->getIdProductConfiguration()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $persistedProductConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
     *
     * @return void
     */
    protected function assertProductConfigurationCollectionIsPaginatedAndSorted(
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer,
        ProductConfigurationCollectionTransfer $persistedProductConfigurationCollectionTransfer,
        ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
    ): void {
        $productConfigurationIds = $this->getProductConfigurationCollectionProductConfigurationIds($productConfigurationCollectionTransfer);
        $expectedProductConfigurationIds = $this->getProductConfigurationCollectionProductConfigurationIds(
            $persistedProductConfigurationCollectionTransfer,
            $productConfigurationCriteriaTransfer,
        );

        $this->assertSame(
            $expectedProductConfigurationIds,
            $productConfigurationIds,
            'Expected to have paginated and sorted transfers in collection but failed.',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer|null $productConfigurationCriteriaTransfer
     *
     * @return list<int>
     */
    protected function getProductConfigurationCollectionProductConfigurationIds(
        ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer,
        ?ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer = null
    ): array {
        $productConfigurationIds = [];

        foreach ($productConfigurationCollectionTransfer->getProductConfigurations() as $productConfigurationTransfer) {
            $productConfigurationIds[] = $productConfigurationTransfer->getIdProductConfiguration();
        }

        if (!$productConfigurationCriteriaTransfer) {
            return $productConfigurationIds;
        }

        return $this->applyProductConfigurationIdsCriteria(
            $productConfigurationIds,
            $productConfigurationCriteriaTransfer,
        );
    }

    /**
     * @param array $productConfigurationIds
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return list<int>
     */
    protected function applyProductConfigurationIdsCriteria(
        array $productConfigurationIds,
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): array {
        if ($productConfigurationCriteriaTransfer->getPagination()) {
            $productConfigurationIds = $this->applyProductConfigurationIdsPagination(
                $productConfigurationIds,
                $productConfigurationCriteriaTransfer->getPagination(),
            );
        }

        if ($productConfigurationCriteriaTransfer->getSortCollection()->offsetExists(0)) {
            $productConfigurationIds = $this->applyProductConfigurationIdsSorting(
                $productConfigurationIds,
                $productConfigurationCriteriaTransfer->getSortCollection()->offsetGet(0),
            );
        }

        return $productConfigurationIds;
    }

    /**
     * @param list<int>|array $productConfigurationIds
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return list<int>
     */
    protected function applyProductConfigurationIdsPagination(
        array $productConfigurationIds,
        ?PaginationTransfer $paginationTransfer = null
    ): array {
        if (!$paginationTransfer) {
            return $productConfigurationIds;
        }

        if ($paginationTransfer->getLimit()) {
            $offset = $paginationTransfer->getOffset() ?? 0;
            $limit = $paginationTransfer->getLimit();
        }

        if ($paginationTransfer->getPage()) {
            $offset = ($paginationTransfer->getPage() - 1) * $paginationTransfer->getMaxPerPage();
            $limit = $paginationTransfer->getMaxPerPage();
        }

        return array_slice($productConfigurationIds, $offset, $limit);
    }

    /**
     * @param list<int>|array $productConfigurationIds
     * @param \Generated\Shared\Transfer\SortTransfer $sortTransfer
     *
     * @return list<int>
     */
    protected function applyProductConfigurationIdsSorting(
        array $productConfigurationIds,
        SortTransfer $sortTransfer
    ): array {
        if ($sortTransfer->getIsAscending()) {
            return $productConfigurationIds;
        }

        return array_reverse($productConfigurationIds);
    }
}
