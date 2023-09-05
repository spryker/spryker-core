<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Client\ClickAndCollectExample\Plugin\ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Spryker\Client\ClickAndCollectExample\Plugin\ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Client\ClickAndCollectExample\ClickAndCollectExampleClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ClickAndCollectExample
 * @group Plugin
 * @group ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin
 * @group CalculateProductOfferServicePointAvailabilitiesTest
 * Add your own group annotations below this line
 */
class CalculateProductOfferServicePointAvailabilitiesTest extends Unit
{
    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_1 = 'merchant-reference-1';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_2 = 'merchant-reference-2';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_1 = 'offer-1';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_2 = 'offer-2';

    /**
     * @var string
     */
    protected const PRODUCT_SKU_1 = 'sku-1';

    /**
     * @var string
     */
    protected const PRODUCT_SKU_2 = 'sku-2';

    /**
     * @var \SprykerTest\Client\ClickAndCollectExample\ClickAndCollectExampleClientTester
     */
    protected ClickAndCollectExampleClientTester $tester;

    /**
     * @return void
     */
    public function testReturnsAvailabilitiesWhenNoOfferAvailabilitiesFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer(
            [
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_2,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
            ],
            [
                ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1,
                ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_2,
            ],
        );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                new ProductOfferServicePointAvailabilityCollectionTransfer(),
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(2, $productOfferServicePointAvailabilities);

        foreach ($productOfferServicePointAvailabilities as $servicePointUuid => $productOfferServicePointAvailabilityResponseItemTransfers) {
            $firstProductOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers[0];
            $secondProductOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers[1];

            $this->assertFalse($firstProductOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
            $this->assertFalse($firstProductOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
            $this->assertSame(0, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
            $this->assertSame($servicePointUuid, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
            $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
            $this->assertSame(static::MERCHANT_REFERENCE_1, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());

            $this->assertFalse($secondProductOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
            $this->assertFalse($secondProductOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
            $this->assertSame(0, $secondProductOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
            $this->assertSame($servicePointUuid, $secondProductOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
            $this->assertSame(static::PRODUCT_OFFER_REFERENCE_2, $secondProductOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        }
    }

    /**
     * @return void
     */
    public function testReturnsFalseAvailabilityForOfferWithMerchantWhenNoApplicableMerchantFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_2)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(10)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            )
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(10)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(0, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testReturnsFalseAvailabilityForOfferWithMerchantWhenApplicableOfferWithLowQuantityFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_1)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(4)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(4, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testUsesAvailabilityFromTheOfferWithTheSameMerchant(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_1)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(4)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(4, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testUsesAnotherOfferAvailabilityForOffersWithMerchantWhenApplicableOfferWithEnoughQuantityFoundFromTheSameMerchant(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_1)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(11)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(11, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(6, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesAvailabilityForOffersWithMerchantWhenApplicableOfferWithLowQuantityFoundFromTheSameMerchant(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_1)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(9)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(9, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(4, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesAvailabilityForOffersWithMerchantWhenApplicableOfferWithNeverOutOfStockFromTheSameMerchant(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_1)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(true)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame(static::MERCHANT_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testReturnsFalseAvailabilityForOfferWithoutMerchantWhenNoApplicableOfferFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setMerchantReference(static::MERCHANT_REFERENCE_1)
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(10)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            )
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_2)
                    ->setAvailableQuantity(10)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(0, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testReturnsFalseAvailabilityForOfferWithoutMerchantWhenApplicableOfferWithLowQuantityFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(4)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(4, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testUsesAvailabilityFromAnotherTheOfferWithoutMerchant(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(5)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(5, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesAvailabilityForOffersWithoutMerchantWhenApplicableOfferWithEnoughQuantityWithoutMerchantFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(11)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(11, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(6, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesAvailabilityForOffersWithoutMerchantWhenApplicableOfferWithLowQuantityWithoutMerchantFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(9)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(9, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(4, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesAvailabilityForOffersWithoutMerchantWhenApplicableOfferWithNeverOutOfStockWithoutMerchantFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(true)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        // Act
        $productOfferServicePointAvailabilities = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference());
        $this->assertSame(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductConcreteSkuPropertyIsMissingInRequestItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenQuantityPropertyIsMissingInRequestItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductOfferReferencePropertyIsMissingInRequestItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenAvailableQuantityPropertyIsMissingInResponseItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenIsNeverOutOfStockPropertyIsMissingInResponseItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(10)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductConcreteSkuPropertyIsMissingInResponseItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setIsNeverOutOfStock(true)
                    ->setAvailableQuantity(10)
                    ->setServicePointUuid(ClickAndCollectExampleClientTester::SERVICE_POINT_UUID_1),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointUuidPropertyIsMissingInResponseItem(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setIsNeverOutOfStock(true)
                    ->setAvailableQuantity(10),
            );

        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }
}
