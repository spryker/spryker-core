<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group Facade
 * @group ProductCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductCartConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacadeInterface
     */
    protected $productCartConnectorFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productCartConnectorFacade = $this->tester->getLocator()->productCartConnector()->facade();
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesValidatesAllOptions()
    {
        // Assign
        $cartPriceMode = $this->getDefaultPriceMode();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();
        $expectedProductOptionErrorCount = 3;

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, null),
            ]
        );
        $productOptionCollection[1] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, null),
            ]
        );
        $productOptionCollection[2] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, null),
            ]
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
                ->addProductOption($productOptionCollection[1])
        );
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku2')
                ->addProductOption($productOptionCollection[2])
        );

        // Act
        $actualResult = $this->productCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedProductOptionErrorCount, $actualResult->getMessages()->count());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesReturnsSuccessFlagWhenNoViolationWasFound()
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = $this->getDefaultPriceMode();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ]
        );
        $productOptionCollection[1] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ]
        );
        $productOptionCollection[2] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ]
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
                ->addProductOption($productOptionCollection[1])
        );
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku2')
                ->addProductOption($productOptionCollection[2])
        );

        // Act
        $actualResponse = $this->productCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesReturnsUnsuccessfulFlagWhenViolationWasFound()
    {
        // Assign
        $expectedResult = false;
        $isCurrentPriceModeNet = $this->getDefaultPriceMode() === $this->getNetPriceModeIdentifier();
        $cartPriceMode = $this->getDefaultPriceMode();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ]
        );
        $productOptionCollection[1] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ]
        );
        $productOptionCollection[2] = $this->createProductOption(
            [
                $this->createPriceData(
                    $currentCurrencyCode,
                    $currentStoreName,
                    // current price mode should not have price, but the other
                    $isCurrentPriceModeNet ? 100 : null,
                    $isCurrentPriceModeNet ? null : 100
                ),
            ]
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
                ->addProductOption($productOptionCollection[1])
        );
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku2')
                ->addProductOption($productOptionCollection[2])
        );

        // Act
        $actualResponse = $this->productCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesUsesDefaultPriceModeForValidationWhenPriceModeIsNotDefined()
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = null;
        $isCurrentPriceModeNet = $this->getDefaultPriceMode() === $this->getNetPriceModeIdentifier();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData(
                    $currentCurrencyCode,
                    $currentStoreName,
                    // only current price mode should have price
                    $isCurrentPriceModeNet ? null : 100,
                    $isCurrentPriceModeNet ? 100 : null
                ),
            ]
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
        );

        // Act
        $actualResponse = $this->productCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesUsesNetPricesForValidationWhenPriceModeIsSetAsNet()
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = $this->getNetPriceModeIdentifier();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, 100),
            ]
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
        );

        // Act
        $actualResponse = $this->productCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesUsesGrossPricesForValidationWhenPriceModeIsSetAsGross()
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = $this->getGrossPriceModeIdentifier();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, null),
            ]
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
        );

        // Act
        $actualResponse = $this->productCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return string
     */
    protected function getDefaultPriceMode()
    {
        return $this->tester->getLocator()->price()->facade()->getDefaultPriceMode();
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        return $this->tester->getLocator()->price()->facade()->getNetPriceModeIdentifier();
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        return $this->tester->getLocator()->price()->facade()->getGrossPriceModeIdentifier();
    }

    /**
     * @return string
     */
    protected function getCurrentCurrencyCode()
    {
        return $this->tester->getLocator()->currency()->facade()->getCurrent()->getCode();
    }

    /**
     * @return string
     */
    protected function getCurrentStoreName()
    {
        return $this->tester->getLocator()->store()->facade()->getCurrentStore()->getName();
    }

    /**
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer($priceMode)
    {
        return (new CartChangeTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setPriceMode($priceMode)
            );
    }

    /**
     * @param string $currencyCode
     * @param string|null $storeName
     * @param int|null $grossAmount
     * @param int|null $netAmount
     *
     * @return array
     */
    protected function createPriceData($currencyCode, $storeName, $grossAmount, $netAmount)
    {
        return [
            ProductOptionGroupDataHelper::CURRENCY_CODE => $currencyCode,
            ProductOptionGroupDataHelper::STORE_NAME => $storeName,
            MoneyValueTransfer::GROSS_AMOUNT => $grossAmount,
            MoneyValueTransfer::NET_AMOUNT => $netAmount,
        ];
    }

    /**
     * @param array $prices
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOption(array $prices)
    {
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    $prices,
                ],
            ]
        );

        $productOptionTransfer = (new ProductOptionTransfer())
            ->setIdProductOptionValue($productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue());

        return $productOptionTransfer;
    }
}
