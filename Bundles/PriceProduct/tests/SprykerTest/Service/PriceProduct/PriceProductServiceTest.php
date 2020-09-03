<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProduct;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProduct
 * @group PriceProductServiceTest
 * Add your own group annotations below this line
 */
class PriceProductServiceTest extends Unit
{
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    protected const TEST_DIMENSION = 'TEST_DIMENSION';
    protected const TEST_ID_CURRENCY = 1;

    /**
     * @var \SprykerTest\Service\PriceProduct\PriceProductTester
     */
    protected $tester;

    /**
     * @dataProvider getPriceProductTransfersWithAllData
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     *
     * @return void
     */
    public function testMergePricesWillReturnConcretePricesOnConcretePriceSet(
        array $concretePriceProductTransfers,
        array $abstractPriceProductTransfers
    ): void {
        $abstractPriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($abstractPriceProductTransfers);
        $concretePriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($concretePriceProductTransfers);
        $priceProductService = $this->getPriceProductService();

        $mergedPriceProductTransfers = $priceProductService->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $concretePriceProductTransfer */
        $concretePriceProductTransfer = $concretePriceProductTransfers[0];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $mergedPriceProductTransfer */
        $mergedPriceProductTransfer = $mergedPriceProductTransfers[array_keys($mergedPriceProductTransfers)[0]];
        $this->assertSame($concretePriceProductTransfer, $mergedPriceProductTransfer);
        $this->assertEquals($concretePriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertEquals($concretePriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer */
        $abstractPriceProductTransfer = $abstractPriceProductTransfers[0];
        $this->assertNotEquals($abstractPriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertNotEquals($abstractPriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());
    }

    /**
     * @dataProvider getPriceProductTransfersWithPartialConcreteData
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     *
     * @return void
     */
    public function testMergePricesWillReturnAbstractPricesOnConcretePriceNotSet(
        array $concretePriceProductTransfers,
        array $abstractPriceProductTransfers
    ): void {
        $abstractPriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($abstractPriceProductTransfers);
        $concretePriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($concretePriceProductTransfers);

        $priceProductService = $this->getPriceProductService();

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $concretePriceProductTransfer */
        $concretePriceProductTransfer = $concretePriceProductTransfers[0];
        $concretePriceProductTransfer->getMoneyValue()->setGrossAmount(null)->setNetAmount(null);

        $mergedPriceProductTransfers = $priceProductService->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $mergedPriceProductTransfer */
        $mergedPriceProductTransfer = $mergedPriceProductTransfers[array_keys($mergedPriceProductTransfers)[1]];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer */
        $abstractPriceProductTransfer = $abstractPriceProductTransfers[1];
        $this->assertSame($abstractPriceProductTransfer, $mergedPriceProductTransfer);
        $this->assertEquals($abstractPriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertEquals($abstractPriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());
    }

    /**
     * @dataProvider getPriceProductTransfersWithPartialConcreteData
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     *
     * @return void
     */
    public function testMergePricesWillReturnPartialAbstractPricesOnSingleConcretePriceSet(
        array $concretePriceProductTransfers,
        array $abstractPriceProductTransfers
    ): void {
        $abstractPriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($abstractPriceProductTransfers);
        $concretePriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($concretePriceProductTransfers);
        $priceProductService = $this->getPriceProductService();

        $mergedPriceProductTransfers = $priceProductService->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $concretePriceProductTransfer */
        $concretePriceProductTransfer = $concretePriceProductTransfers[0];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer */
        $abstractPriceProductTransfer = $abstractPriceProductTransfers[0];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $mergedPriceProductTransfer */
        $mergedPriceProductTransfer = $mergedPriceProductTransfers[array_keys($mergedPriceProductTransfers)[0]];
        $this->assertSame($concretePriceProductTransfer, $mergedPriceProductTransfer);
        $this->assertEquals($concretePriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertEquals($concretePriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());
        $this->assertNotEquals($abstractPriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertNotEquals($abstractPriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $mergedPriceProductTransfer */
        $mergedPriceProductTransfer = $mergedPriceProductTransfers[array_keys($mergedPriceProductTransfers)[1]];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer */
        $abstractPriceProductTransfer = $abstractPriceProductTransfers[1];
        $this->assertSame($abstractPriceProductTransfer, $mergedPriceProductTransfer);
        $this->assertEquals($abstractPriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertEquals($abstractPriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());
    }

    /**
     * @dataProvider getPriceProductTransfersWithMoreConcreteData
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     *
     * @return void
     */
    public function testMergePricesWillReturnExtraConcretePriceSet(
        array $concretePriceProductTransfers,
        array $abstractPriceProductTransfers
    ): void {
        $abstractPriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($abstractPriceProductTransfers);
        $concretePriceProductTransfers = $this->prefillTransferWithDataForPriceGrouping($concretePriceProductTransfers);
        $priceProductService = $this->getPriceProductService();

        $mergedPriceProductTransfers = $priceProductService->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $concretePriceProductTransfer */
        $concretePriceProductTransfer = $concretePriceProductTransfers[0];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer */
        $abstractPriceProductTransfer = $abstractPriceProductTransfers[0];
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $mergedPriceProductTransfer */
        $mergedPriceProductTransfer = $mergedPriceProductTransfers[array_keys($mergedPriceProductTransfers)[0]];
        $this->assertSame($concretePriceProductTransfer, $mergedPriceProductTransfer);
        $this->assertEquals($concretePriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertEquals($concretePriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());
        $this->assertNotEquals($abstractPriceProductTransfer->getMoneyValue()->getGrossAmount(), $mergedPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertNotEquals($abstractPriceProductTransfer->getMoneyValue()->getNetAmount(), $mergedPriceProductTransfer->getMoneyValue()->getNetAmount());

        $this->assertCount(4, $mergedPriceProductTransfers);
    }

    /**
     * @return void
     */
    public function testResolveProductPriceByPriceProductCriteriaIgnoresPricesWithEmptyValueForRequestedPriceType(): void
    {
        // Arrange
        $priceWithNetAmount = (new PriceProductTransfer())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setNetAmount(100)
                    ->setCurrency(
                        (new CurrencyTransfer())
                            ->setIdCurrency(static::TEST_ID_CURRENCY)
                    )
            )
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(static::TEST_DIMENSION)
            );

        $priceWithoutNetAmount = clone $priceWithNetAmount;
        $priceWithoutNetAmount->setMoneyValue(
            (clone $priceWithNetAmount->getMoneyValue())
                ->setNetAmount(null)
        );

        $priceProductTransfers = [
            $priceWithoutNetAmount,
            $priceWithNetAmount,
        ];

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setPriceType(static::PRICE_TYPE_DEFAULT)
            ->setIdCurrency(static::TEST_ID_CURRENCY);

        // Act
        $result = $this->getPriceProductService()->resolveProductPriceByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);

        // Assert
        $this->assertEquals($priceWithNetAmount, $result);
    }

    /**
     * @return void
     */
    public function testResolveProductPriceByPriceProductCriteriaReturnsNullIfThereAreNoPricesWithRequestedType(): void
    {
        // Arrange
        $priceProductTransferGrossFirst = (new PriceProductTransfer())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setGrossAmount(100)
                    ->setCurrency(
                        (new CurrencyTransfer())
                            ->setIdCurrency(static::TEST_ID_CURRENCY)
                    )
            )
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(static::TEST_DIMENSION)
            );

        $priceProductTransferGrossSecond = clone $priceProductTransferGrossFirst;
        $priceProductTransferGrossSecond->setMoneyValue(
            (clone $priceProductTransferGrossSecond->getMoneyValue())
                ->setGrossAmount(200)
        );

        $priceProductTransfers = [
            $priceProductTransferGrossFirst,
            $priceProductTransferGrossSecond,
        ];

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setPriceType(static::PRICE_TYPE_DEFAULT)
            ->setIdCurrency(static::TEST_ID_CURRENCY);

        // Act
        $result = $this->getPriceProductService()->resolveProductPriceByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);

        // Assert
        $this->assertEquals(null, $result);
    }

    /**
     * @return void
     */
    public function testResolveProductPriceByPriceProductCriteriaReturnsPriceMatchedByType(): void
    {
        // Arrange
        $priceProductTransferNet = (new PriceProductTransfer())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setNetAmount(100)
                    ->setCurrency(
                        (new CurrencyTransfer())
                            ->setIdCurrency(static::TEST_ID_CURRENCY)
                    )
            )
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(static::TEST_DIMENSION)
            );

        $priceProductTransfers = [
            $priceProductTransferNet,
        ];

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setPriceType(static::PRICE_TYPE_DEFAULT)
            ->setIdCurrency(static::TEST_ID_CURRENCY);

        // Act
        $result = $this->getPriceProductService()->resolveProductPriceByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);

        // Assert
        $this->assertEquals($priceProductTransferNet, $result);
    }

    /**
     * @return \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected function getPriceProductService(): PriceProductServiceInterface
    {
        return $this->tester->getLocator()->priceProduct()->service();
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    protected function getSinglePriceProductTransfers(): array
    {
        return [
            (new PriceProductBuilder(['priceTypeName' => 'DEFAULT']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency())
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    protected function getMultiplePriceProductTransfers(): array
    {
        $chfCurrencyData = ['code' => 'CHF', 'name' => 'CHF', 'symbol' => 'CHF'];

        return [
            (new PriceProductBuilder(['priceTypeName' => 'DEFAULT']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency())
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
            (new PriceProductBuilder(['priceTypeName' => 'ORIGINAL']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency())
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
            (new PriceProductBuilder(['priceTypeName' => 'DEFAULT']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency($chfCurrencyData))
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
            (new PriceProductBuilder(['priceTypeName' => 'ORIGINAL']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency($chfCurrencyData))
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
        ];
    }

    /**
     * @return array
     */
    public function getPriceProductTransfersWithAllData(): array
    {
        return [
            [$this->getMultiplePriceProductTransfers(), $this->getMultiplePriceProductTransfers()],
        ];
    }

    /**
     * @return array
     */
    public function getPriceProductTransfersWithPartialConcreteData(): array
    {
        return [
            [$this->getSinglePriceProductTransfers(), $this->getMultiplePriceProductTransfers()],
        ];
    }

    /**
     * @return array
     */
    public function getPriceProductTransfersWithMoreConcreteData(): array
    {
        return [
            [$this->getMultiplePriceProductTransfers(), $this->getSinglePriceProductTransfers()],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function prefillTransferWithDataForPriceGrouping(array $priceProductTransfers): array
    {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfer->setIsMergeable(true)
                ->setGroupKey(
                    $this->getPriceProductService()->buildPriceProductGroupKey($priceProductTransfer)
                );
        }

        return $priceProductTransfers;
    }
}
