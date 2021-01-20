<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Business;

use Closure;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use ReflectionProperty;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMap;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacade;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface;

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

    protected const FORMAT_SUPER_ATTRIBUTE = 'attribute_%d';
    protected const FORMAT_SUPER_ATTRIBUTE_VALUE = 'value_%d_%d';

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

        $this->resetAttributeMapSuperAttributesCache();
    }

    /**
     * @dataProvider attributeMapDataProvider
     *
     * @param int $attributeKeyCount
     * @param bool $isProductAttributesWithSingleValueIncluded
     * @param \Closure $expectedAttributeVariantsMapClosure
     *
     * @return void
     */
    public function testPublishAbstractProductsBuildsCorrectAttributeVariantsMap(
        int $attributeKeyCount,
        bool $isProductAttributesWithSingleValueIncluded,
        Closure $expectedAttributeVariantsMapClosure
    ): void {
        //Arrange
        $productConcreteAttributes = [];

        for ($i = 1; $i <= $attributeKeyCount; $i++) {
            $superAttributeKey = sprintf(static::FORMAT_SUPER_ATTRIBUTE, $i);

            $this->tester->haveProductManagementAttributeEntity([], [
                static::IS_SUPER_ATTRIBUTE_KEY => true,
                static::ATTRIBUTE_KEY => $superAttributeKey,
            ]);

            $productConcreteAttributes[$superAttributeKey] = sprintf(static::FORMAT_SUPER_ATTRIBUTE_VALUE, $i, 1);
        }

        $productConcreteTransfer1 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => $productConcreteAttributes,
        ]);

        $productConcreteTransfer2 = $this->cloneProductConcrete($productConcreteTransfer1);
        $productConcreteTransfer2 = $this->tester->haveProductConcrete($productConcreteTransfer2->toArray());

        // Act
        $this->getProductStorageFacade($isProductAttributesWithSingleValueIncluded)
            ->publishAbstractProducts([$productConcreteTransfer1->getFkProductAbstract()]);

        $productAbstractStorageEntity = SpyProductAbstractStorageQuery::create()
            ->filterByFkProductAbstract($productConcreteTransfer1->getFkProductAbstract())
            ->findOne();

        $attributeVariantsMap = $productAbstractStorageEntity->toArray()[static::DATA_KEY][static::ATTRIBUTE_MAP_KEY][static::ATTRIBUTE_VARIANTS_KEY];

        $expectedAttributeVariantsMap = $expectedAttributeVariantsMapClosure(
            $productConcreteTransfer1->getIdProductConcrete(),
            $productConcreteTransfer2->getIdProductConcrete()
        );

        // Assert
        $this->assertSame(
            $expectedAttributeVariantsMap,
            $attributeVariantsMap,
            'Expected that generated attribute variants map match'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer1
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function cloneProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer1
    ): ProductConcreteTransfer {
        $productConcreteTransfer2 = clone $productConcreteTransfer1;
        $productConcreteTransfer2
            ->setIdProductConcrete(null)
            ->setSku(uniqid('SKU', true));

        $productConcreteAttributes = $productConcreteTransfer1->getAttributes();
        $superAttributeKeyNumberToReplace = 2;
        $productConcreteAttributes[sprintf(static::FORMAT_SUPER_ATTRIBUTE, $superAttributeKeyNumberToReplace)] = sprintf(
            static::FORMAT_SUPER_ATTRIBUTE_VALUE,
            $superAttributeKeyNumberToReplace,
            2
        );

        $productConcreteTransfer2
            ->setAttributes($productConcreteAttributes)
            ->getLocalizedAttributes()
            ->offsetGet(0)
            ->setAttributes($productConcreteAttributes);

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

    /**
     * @param bool $isProductAttributesWithSingleValueIncluded
     *
     * @return \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface
     */
    protected function getProductStorageFacade(
        bool $isProductAttributesWithSingleValueIncluded = true
    ): ProductStorageFacadeInterface {
        $configMock = $this->tester->mockConfigMethod(
            'isProductAttributesWithSingleValueIncluded',
            function () use ($isProductAttributesWithSingleValueIncluded) {
                return $isProductAttributesWithSingleValueIncluded;
            }
        );

        $productStorageBusinessFactory = new ProductStorageBusinessFactory();
        $productStorageBusinessFactory->setConfig($configMock);

        $productStorageFacade = new ProductStorageFacade();
        $productStorageFacade->setFactory($productStorageBusinessFactory);

        return $productStorageFacade;
    }

    /**
     * @return array[]
     */
    public function attributeMapDataProvider(): array
    {
        return [
            'attribute map with single values included' => [
                'attributeKeyCount' => 2,
                'configValue' => true,
                'closure' => function (int $productConcreteId, int $productConcreteTwoId): array {
                    return [
                        'attribute_1:value_1_1' => [
                            'attribute_2:value_2_1' => [
                                'id_product_concrete' => $productConcreteId,
                            ],
                            'attribute_2:value_2_2' => [
                                'id_product_concrete' => $productConcreteTwoId,
                            ],
                        ],
                        'attribute_2:value_2_1' => [
                            'attribute_1:value_1_1' => [
                                'id_product_concrete' => $productConcreteId,
                            ],
                        ],
                        'attribute_2:value_2_2' => [
                            'attribute_1:value_1_1' => [
                                'id_product_concrete' => $productConcreteTwoId,
                            ],
                        ],
                    ];
                },
            ],
            'attribute map with single values excluded' => [
                'attributeKeyCount' => 2,
                'configValue' => false,
                'closure' => function (int $productConcreteId, int $productConcreteTwoId): array {
                    return [
                        'attribute_2:value_2_1' => [
                            'id_product_concrete' => $productConcreteId,
                        ],
                        'attribute_2:value_2_2' => [
                            'id_product_concrete' => $productConcreteTwoId,
                        ],
                    ];
                },
            ],
            'attribute map with single values excluded (9 super attributes case)' => [
                'attributeKeyCount' => 9,
                'configValue' => false,
                'closure' => function (int $productConcreteId, int $productConcreteTwoId): array {
                    return [
                        'attribute_2:value_2_1' => [
                            'id_product_concrete' => $productConcreteId,
                        ],
                        'attribute_2:value_2_2' => [
                            'id_product_concrete' => $productConcreteTwoId,
                        ],
                    ];
                },
            ],
        ];
    }
}
