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

    protected const SUPER_ATTRIBUTE_NAME_PREFIX = 'attribute';

    protected const SUPER_ATTRIBUTE_ONE = 'attribute_1';
    protected const SUPER_ATTRIBUTE_TWO = 'attribute_2';

    protected const SUPER_ATTRIBUTE_ONE_VALUE = 'value_1';
    protected const SUPER_ATTRIBUTE_TWO_VALUE = 'value_2';
    protected const SUPER_ATTRIBUTE_TWO_SECOND_VALUE = 'value_3';

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
     * @return void
     */
    public function PublishAbstractProductsWithEnabledSingleValueAttributePermutationShouldBuildAttributeVariantsMap(): void
    {
        //Arrange
        $this->tester->haveProductManagementAttributeEntity([], [
            static::IS_SUPER_ATTRIBUTE_KEY => true,
            static::ATTRIBUTE_KEY => static::SUPER_ATTRIBUTE_ONE,
        ]);

        $this->tester->haveProductManagementAttributeEntity([], [
            static::IS_SUPER_ATTRIBUTE_KEY => true,
            static::ATTRIBUTE_KEY => static::SUPER_ATTRIBUTE_TWO,
        ]);

        $productConcreteTransfer1 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => [
                static::SUPER_ATTRIBUTE_ONE => static::SUPER_ATTRIBUTE_ONE_VALUE,
                static::SUPER_ATTRIBUTE_TWO => static::SUPER_ATTRIBUTE_TWO_VALUE,
            ],
        ]);

        $productConcreteTransfer2 = $this->cloneProductConcrete($productConcreteTransfer1);
        $productConcreteTransfer2 = $this->tester->haveProductConcrete($productConcreteTransfer2->toArray());

        // Act
        $this->getProductStorageFacade(true)
            ->publishAbstractProducts([$productConcreteTransfer1->getFkProductAbstract()]);

        $productAbstractStorageEntity = SpyProductAbstractStorageQuery::create()
            ->filterByFkProductAbstract($productConcreteTransfer1->getFkProductAbstract())
            ->findOne();

        $attributeVariantsMap = $productAbstractStorageEntity->toArray()[static::DATA_KEY][static::ATTRIBUTE_MAP_KEY][static::ATTRIBUTE_VARIANTS_KEY];

        $expectedAttributeVariantsMap = $this->getAttributeMapWithSingleValuePermutation(
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
     * @return void
     */
    public function testPublishAbstractProductsWithDisabledSingleValueAttributePermutationShouldBuildAttributeVariantsMap(
    ): void
    {
        //Arrange
        $this->tester->haveProductManagementAttributeEntity([], [
            static::IS_SUPER_ATTRIBUTE_KEY => true,
            static::ATTRIBUTE_KEY => static::SUPER_ATTRIBUTE_ONE,
        ]);

        $this->tester->haveProductManagementAttributeEntity([], [
            static::IS_SUPER_ATTRIBUTE_KEY => true,
            static::ATTRIBUTE_KEY => static::SUPER_ATTRIBUTE_TWO,
        ]);

        $productConcreteTransfer1 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => [
                static::SUPER_ATTRIBUTE_ONE => static::SUPER_ATTRIBUTE_ONE_VALUE,
                static::SUPER_ATTRIBUTE_TWO => static::SUPER_ATTRIBUTE_TWO_VALUE,
            ],
        ]);

        $productConcreteTransfer2 = $this->cloneProductConcrete($productConcreteTransfer1);
        $productConcreteTransfer2 = $this->tester->haveProductConcrete($productConcreteTransfer2->toArray());

        // Act
        $this->getProductStorageFacade(false)
            ->publishAbstractProducts([$productConcreteTransfer1->getFkProductAbstract()]);

        $productAbstractStorageEntity = SpyProductAbstractStorageQuery::create()
            ->filterByFkProductAbstract($productConcreteTransfer1->getFkProductAbstract())
            ->findOne();

        $attributeVariantsMap = $productAbstractStorageEntity->toArray()[static::DATA_KEY][static::ATTRIBUTE_MAP_KEY][static::ATTRIBUTE_VARIANTS_KEY];

        $expectedAttributeVariantsMap = $this->getAttributeMapWithoutSingleValuePermutation(
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

        $attributes = [
            static::SUPER_ATTRIBUTE_ONE => static::SUPER_ATTRIBUTE_ONE_VALUE,
            static::SUPER_ATTRIBUTE_TWO => static::SUPER_ATTRIBUTE_TWO_SECOND_VALUE,
        ];

        $productConcreteTransfer2
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

    /**
     * @param bool $enableSingleValueAttributePermutation
     *
     * @return \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface
     */
    protected function getProductStorageFacade(bool $enableSingleValueAttributePermutation = true
    ): ProductStorageFacadeInterface {
        $configMock = $this->tester->mockConfigMethod('isPermutationForSingleValueProductAttributesEnabled',
            function () use ($enableSingleValueAttributePermutation) {
                return $enableSingleValueAttributePermutation;
            });

        $productStorageBusinessFactory = new ProductStorageBusinessFactory();
        $productStorageBusinessFactory->setConfig($configMock);

        $productStorageFacade = new ProductStorageFacade();
        $productStorageFacade->setFactory($productStorageBusinessFactory);

        return $productStorageFacade;
    }

    /**
     * @param int $productConcreteId
     * @param int $productConcreteTwoId
     *
     * @return array
     */
    protected function getAttributeMapWithSingleValuePermutation(
        int $productConcreteId,
        int $productConcreteTwoId
    ): array {
        return [
            'attribute_1:value_1' => [
                'attribute_2:value_2' => [
                    "id_product_concrete" => $productConcreteId
                ],
                'attribute_2:value_3' => [
                    "id_product_concrete" => $productConcreteTwoId
                ]
            ],
            'attribute_2:value_2' => [
                'attribute_1:value_1' => [
                    'id_product_concrete' => $productConcreteId
                ]
            ],
            'attribute_2:value_3' => [
                'attribute_1:value_1' => [
                    'id_product_concrete' => $productConcreteTwoId
                ]
            ],
        ];
    }

    /**
     * @param int $productConcreteId
     * @param int $productConcreteTwoId
     *
     * @return array
     */
    protected function getAttributeMapWithoutSingleValuePermutation(
        int $productConcreteId,
        int $productConcreteTwoId
    ): array {
        return [
            "attribute_2:value_2" => [
                "id_product_concrete" => $productConcreteId
            ],
            "attribute_2:value_3" => [
                "id_product_concrete" => $productConcreteTwoId
            ],
        ];
    }
}
