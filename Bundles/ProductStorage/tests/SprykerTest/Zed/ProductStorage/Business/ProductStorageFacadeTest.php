<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use ReflectionProperty;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMap;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductStorage
 * @group Business
 * @group Facade
 * @group ProductStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductStorageFacadeTest extends Unit
{
    protected const IS_SUPER_ATTRIBUTE_KEY = 'is_super';
    protected const ATTRIBUTE_KEY = 'key';

    protected const ATTRIBUTE_ONE = 'attribute_1';
    protected const ATTRIBUTE_TWO = 'attribute_2';
    protected const ATTRIBUTE_ONE_VALUE = 'value_1';
    protected const ATTRIBUTE_TWO_VALUE = 'value_2';
    protected const ATTRIBUTE_TWO_SECOND_VALUE = 'value_3';

    protected const ATTRIBUTE_MAP_KEY = 'attribute_map';
    protected const ATTRIBUTE_VARIANTS_KEY = 'attribute_variants';
    protected const DATA_KEY = 'data';

    /**
     * @var \SprykerTest\Zed\ProductStorage\ProductStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @dataProvider publishAbstractProductsShouldBuildAttributeVariantsMapDataProvider
     *
     * @param bool $enableSingleValueAttributePermutation
     * @param int $expectedCount
     *
     * @return void
     */
    public function testPublishAbstractProductsShouldBuildAttributeVariantsMap(
        bool $enableSingleValueAttributePermutation,
        int $expectedCount
    ): void {
        //Arrange
        $this->resetAttributeMapSuperAttributesCache();
        $attributeOneKey = uniqid(static::ATTRIBUTE_ONE, true);
        $attributeTwoKey = uniqid(static::ATTRIBUTE_TWO, true);

        foreach ([$attributeOneKey, $attributeTwoKey] as $attributeKey) {
            $this->tester->haveProductManagementAttributeEntity([], [
                static::IS_SUPER_ATTRIBUTE_KEY => true,
                static::ATTRIBUTE_KEY => $attributeKey,
            ]);
        }

        $productConcreteTransfer1 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => [
                $attributeOneKey => static::ATTRIBUTE_ONE_VALUE,
                $attributeTwoKey => static::ATTRIBUTE_TWO_VALUE,
            ],
        ]);

        $productConcreteTransfer2 = $this->cloneProductConcrete(
            $productConcreteTransfer1,
            $attributeOneKey,
            $attributeTwoKey
        );

        $this->tester->haveProductConcrete($productConcreteTransfer2->toArray());

        // Act
        $this->tester
            ->getProductStorageFacade($enableSingleValueAttributePermutation)
            ->publishAbstractProducts([$productConcreteTransfer1->getFkProductAbstract()]);

        $productAbstractStorageEntity = SpyProductAbstractStorageQuery::create()
            ->filterByFkProductAbstract($productConcreteTransfer1->getFkProductAbstract())
            ->findOne();

        $attributeVariantsMap = $productAbstractStorageEntity->toArray()[static::DATA_KEY][static::ATTRIBUTE_MAP_KEY][static::ATTRIBUTE_VARIANTS_KEY];

        // Assert
        $this->assertCount(
            $expectedCount,
            $attributeVariantsMap,
            sprintf('Expected that attribute variant map will contain %d elements.', $expectedCount)
        );
    }

    /**
     * @return array[]
     */
    public function publishAbstractProductsShouldBuildAttributeVariantsMapDataProvider(): array
    {
        return [
            [true, 3], //enabled single-value attribute permutation
            [false, 2], //disabled single-value attribute permutation
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer1
     * @param string $attributeOneKey
     * @param string $attributeTwoKey
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function cloneProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer1,
        string $attributeOneKey,
        string $attributeTwoKey
    ): ProductConcreteTransfer {
        $attributes = [
            $attributeOneKey => static::ATTRIBUTE_ONE_VALUE,
            $attributeTwoKey => static::ATTRIBUTE_TWO_SECOND_VALUE,
        ];

        $productConcreteTransfer2 = clone $productConcreteTransfer1;
        $productConcreteTransfer2->setIdProductConcrete(null)
            ->setSku(uniqid('SKU', true))
            ->setAttributes($attributes)
            ->getLocalizedAttributes()
            ->offsetGet(0)
            ->setAttributes($attributes);

        return $productConcreteTransfer2;
    }

    /**
     * @return void
     */
    protected function resetAttributeMapSuperAttributesCache(): void
    {
        $reflection = new ReflectionProperty(AttributeMap::class, 'superAttributesCache');
        $reflection->setAccessible(true);
        $reflection->setValue(null, null);
    }
}
