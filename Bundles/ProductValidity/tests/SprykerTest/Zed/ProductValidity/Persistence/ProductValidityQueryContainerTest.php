<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductValidity\Persistence;

use Codeception\Test\Unit;
use DateTime;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainer;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface;
use SprykerTest\Zed\ProductValidity\ProductValidityPersistenceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductValidity
 * @group Persistence
 * @group ProductValidityQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductValidityQueryContainerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductValidity\ProductValidityPersistenceTester
     */
    protected ProductValidityPersistenceTester $tester;

    /**
     * @var \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface
     */
    protected ProductValidityQueryContainerInterface $queryContainer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();
        $this->queryContainer = new ProductValidityQueryContainer();
    }

    /**
     * @return void
     */
    public function testQueryProductsBecomingValidReturnsOnlyInactiveProducts(): void
    {
        // Arrange
        $productIds = $this->createTestProducts();
        $currentDate = new DateTime('now');
        $pastDate = (clone $currentDate)->modify('-1 day');
        $futureDate = (clone $currentDate)->modify('+1 day');

        $this->tester->haveProductValidity([
            'fk_product' => $productIds['inactive_1'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => null,
        ]);
        $this->tester->haveProductValidity([
            'fk_product' => $productIds['inactive_2'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => $futureDate->format('Y-m-d H:i:s'),
        ]);
        $this->tester->haveProductValidity([
            'fk_product' => $productIds['active_1'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => null,
        ]);
        $this->tester->haveProductValidity([
            'fk_product' => $productIds['active_2'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => $futureDate->format('Y-m-d H:i:s'),
        ]);

        // Act
        $productValidityEntities = $this->queryContainer->queryProductsBecomingValid()->find();

        // Assert
        $this->assertCount(2, $productValidityEntities, 'Should find exactly 2 product validity entries for inactive products');

        $foundProductIds = [];
        foreach ($productValidityEntities as $productValidityEntity) {
            $foundProductIds[] = $productValidityEntity->getFkProduct();
            $this->assertFalse(
                $productValidityEntity->getSpyProduct()->getIsActive(),
                sprintf('Product with ID %d should be inactive', $productValidityEntity->getFkProduct()),
            );
        }

        $this->assertContains(
            $productIds['inactive_1'],
            $foundProductIds,
            'Inactive product 1 should be in results',
        );
        $this->assertContains(
            $productIds['inactive_2'],
            $foundProductIds,
            'Inactive product 2 should be in results',
        );
        $this->assertNotContains(
            $productIds['active_1'],
            $foundProductIds,
            'Active product 1 should not be in results',
        );
        $this->assertNotContains(
            $productIds['active_2'],
            $foundProductIds,
            'Active product 2 should not be in results',
        );

        $this->cleanup(
            $productValidityEntities,
            [
                $productIds['active_1'],
                $productIds['active_2'],
            ],
        );
    }

    /**
     * @return void
     */
    public function testQueryProductsBecomingInvalidReturnsOnlyActiveProducts(): void
    {
        // Arrange
        $productIds = $this->createTestProducts();
        $currentDate = new DateTime('now');
        $pastDate = (clone $currentDate)->modify('-2 days');
        $futureDate = (clone $currentDate)->modify('+1 day');
        $validityEndDate = (clone $currentDate)->modify('-1 day');

        $this->tester->haveProductValidity([
            'fk_product' => $productIds['active_1'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => $validityEndDate->format('Y-m-d H:i:s'),
        ]);
        $this->tester->haveProductValidity([
            'fk_product' => $productIds['active_2'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => $futureDate->format('Y-m-d H:i:s'),
        ]);
        $this->tester->haveProductValidity([
            'fk_product' => $productIds['inactive_1'],
            'valid_from' => $futureDate->format('Y-m-d H:i:s'),
            'valid_to' => $futureDate->modify('+1 day')->format('Y-m-d H:i:s'),
        ]);
        $this->tester->haveProductValidity([
            'fk_product' => $productIds['inactive_2'],
            'valid_from' => $pastDate->format('Y-m-d H:i:s'),
            'valid_to' => $validityEndDate->format('Y-m-d H:i:s'),
        ]);

        // Act
        $productValidityEntities = $this->queryContainer->queryProductsBecomingInvalid()->find();

        // Assert
        $this->assertCount(1, $productValidityEntities, 'Should find exactly 1 product validity entry for active products becoming invalid');

        $foundProductIds = [];
        foreach ($productValidityEntities as $productValidityEntity) {
            $foundProductIds[] = $productValidityEntity->getFkProduct();
            $this->assertTrue(
                $productValidityEntity->getSpyProduct()->getIsActive(),
                sprintf('Product with ID %d should be active', $productValidityEntity->getFkProduct()),
            );
        }

        $this->assertContains(
            $productIds['active_1'],
            $foundProductIds,
            'Active product becoming invalid should be in results',
        );
        $this->assertNotContains(
            $productIds['active_2'],
            $foundProductIds,
            'Active product that remains valid should not be in results',
        );
        $this->assertNotContains(
            $productIds['inactive_1'],
            $foundProductIds,
            'Inactive product should not be in results',
        );
        $this->assertNotContains(
            $productIds['inactive_2'],
            $foundProductIds,
            'Inactive product should not be in results',
        );

        $this->cleanup(
            $productValidityEntities,
            array_values($productIds),
        );
    }

    /**
     * @return array<string, int>
     */
    protected function createTestProducts(): array
    {
        return [
            'active_1' => $this->tester->haveProduct(['is_active' => true])->getIdProductConcrete(),
            'active_2' => $this->tester->haveProduct(['is_active' => true])->getIdProductConcrete(),
            'inactive_1' => $this->tester->haveProduct(['is_active' => false])->getIdProductConcrete(),
            'inactive_2' => $this->tester->haveProduct(['is_active' => false])->getIdProductConcrete(),
        ];
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productValidityEntities
     * @param array<int> $productConcreteIds
     *
     * @return void
     */
    protected function cleanup(ObjectCollection $productValidityEntities, array $productConcreteIds): void
    {
        $this->tester->addCleanup(function () use ($productValidityEntities, $productConcreteIds): void {
            $productValidityEntities->delete();

            foreach ($productConcreteIds as $idProductConcrete) {
                $this->queryContainer
                    ->queryProductValidityByIdProductConcrete($idProductConcrete)
                    ->delete();
            }
        });
    }
}
