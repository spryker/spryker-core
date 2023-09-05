<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Client\ClickAndCollectExample\Plugin\ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use Spryker\Client\ClickAndCollectExample\Plugin\ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin;
use SprykerTest\Client\ClickAndCollectExample\ClickAndCollectExampleClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ClickAndCollectExample
 * @group Plugin
 * @group ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin
 * @group IsApplicableTest
 * Add your own group annotations below this line
 */
class IsApplicableTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE = 'offer-1';

    /**
     * @var string
     */
    protected const PRODUCT_SKU = 'sku-1';

    /**
     * @var \SprykerTest\Client\ClickAndCollectExample\ClickAndCollectExampleClientTester
     */
    protected ClickAndCollectExampleClientTester $tester;

    /**
     * @return void
     */
    public function testReturnsFalseWhenServicePointsAreNotProvided(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer(
            [
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
                ],
            ],
            [],
        );

        // Act
        $isApplicable = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->isApplicable(
                new ProductOfferServicePointAvailabilityCollectionTransfer(),
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenRequestItemsAreNotProvided(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer();

        // Act
        $isApplicable = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->isApplicable(
                new ProductOfferServicePointAvailabilityCollectionTransfer(),
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testReturnsTrueWhenNeededDataIsProvided(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityConditionsTransfer = $this->tester->createProductOfferServicePointAvailabilityConditionsTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $isApplicable = (new ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin())
            ->isApplicable(
                new ProductOfferServicePointAvailabilityCollectionTransfer(),
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

        // Assert
        $this->assertTrue($isApplicable);
    }
}
