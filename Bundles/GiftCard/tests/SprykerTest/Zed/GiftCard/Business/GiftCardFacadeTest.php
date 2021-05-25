<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\GiftCardBuilder;
use Generated\Shared\DataBuilder\GiftCardMetadataBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ShipmentGroupBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodsBuilder;
use Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer;
use Generated\Shared\Transfer\GiftCardMetadataTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory;
use Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface;
use Spryker\Zed\GiftCard\GiftCardConfig;
use Spryker\Zed\GiftCard\GiftCardDependencyProvider;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group GiftCardFacadeTest
 * Add your own group annotations below this line
 */
class GiftCardFacadeTest extends Test
{
    protected const TEST_GIFT_CARD_CODE = 'testCode';

    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindByIdShouldReturnTransferObjectForExistingGiftCard(): void
    {
        $giftCardTransfer = $this->tester->haveGiftCard(['attributes' => []]);

        $foundGiftCardTransfer = $this->getFacade()->findById($giftCardTransfer->getIdGiftCard());

        $this->assertNotNull($foundGiftCardTransfer);
        $this->assertSame($giftCardTransfer->getIdGiftCard(), $foundGiftCardTransfer->getIdGiftCard());
    }

    /**
     * @return void
     */
    public function testCreateShouldAssertRequiredTransferObjectFields(): void
    {
        $giftCardTransfer = (new GiftCardBuilder([
            'attributes' => [],
            'value' => null,
        ]))->build();

        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessageMatches('/^Missing required property "value" for transfer/');
        $this->getFacade()->create($giftCardTransfer);
    }

    /**
     * @return void
     */
    public function testCreateShouldPersistGiftCard(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer */
        $giftCardTransfer = (new GiftCardBuilder([
            'attributes' => [],
            'idGiftCard' => null,
        ]))->build();

        // Act
        $this->getFacade()->create($giftCardTransfer);

        // Assert
        $this->assertNotNull($giftCardTransfer->getIdGiftCard());

        $createdGiftCardTransfer = $this->getFacade()->findById($giftCardTransfer->getIdGiftCard());

        $this->assertSame($giftCardTransfer->getCode(), $createdGiftCardTransfer->getCode());
        $this->assertSame($giftCardTransfer->getName(), $createdGiftCardTransfer->getName());
        $this->assertEquals($giftCardTransfer->getValue(), $createdGiftCardTransfer->getValue());
        $this->assertEquals($giftCardTransfer->getIsActive(), $createdGiftCardTransfer->getIsActive());
    }

    /**
     * @dataProvider filterShipmentGroupMethodsShouldRemoveAllowedShipmentMethodsDataProvider
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param string[] $expectedAllowedShipmentMethodNames
     * @param string[] $expectedDisallowedShipmentMethodNames
     *
     * @return void
     */
    public function testFilterShipmentGroupMethodsShouldRemoveAllowedShipmentMethods(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $expectedAllowedShipmentMethodNames,
        array $expectedDisallowedShipmentMethodNames
    ): void {
        // Arrange
        $facade = $this->getFacadeWithMockedConfig();

        // Act
        $actualShipmentMethodTransfers = $facade->filterShipmentGroupMethods($shipmentGroupTransfer);

        // Assert
        foreach ($actualShipmentMethodTransfers as $actualShipmentMethodTransfer) {
            $this->assertContains($actualShipmentMethodTransfer->getName(), $expectedAllowedShipmentMethodNames);
            $this->assertNotContains($actualShipmentMethodTransfer->getName(), $expectedDisallowedShipmentMethodNames);
        }
    }

    /**
     * @return array
     */
    public function filterShipmentGroupMethodsShouldRemoveAllowedShipmentMethodsDataProvider(): array
    {
        return [
            'shipment group: only gift card items; expected: only NoShipment method' => $this->getDataWithOnlyGiftCardItems(),
            'shipment group: not only gift card items; expected: all methods except NoShipment method' => $this->getDataWithNotOnlyGiftCardItems(),
        ];
    }

    /**
     * @return void
     */
    public function testAddCartCodeAddsGiftCardToQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithoutGiftCard();

        // Act
        $resultQuoteTransfer = $this->getFacade()->addCartCode($quoteTransfer, $this->tester::GIFT_CARD_CODE);

        // Assert
        $this->assertCount(1, $quoteTransfer->getGiftCards());
        $this->assertSame(
            $this->tester::GIFT_CARD_CODE,
            $resultQuoteTransfer->getGiftCards()[0]->getCode()
        );
    }

    /**
     * @return void
     */
    public function testAddCartCodeCantAddGiftCardToQuoteWithGiftCardAlreadyAddedToQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithGiftCard();

        // Act
        $resultQuoteTransfer = $this->getFacade()->addCartCode($quoteTransfer, $this->tester::GIFT_CARD_CODE);

        // Assert
        $this->assertCount(1, $resultQuoteTransfer->getGiftCards());
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeRemovesGiftCardFromQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithGiftCard();

        // Act
        $resultQuoteTransfer = $this->getFacade()->removeCartCode($quoteTransfer, $this->tester::GIFT_CARD_CODE);

        // Assert
        $this->assertCount(0, $resultQuoteTransfer->getGiftCards());
    }

    /**
     * @return void
     */
    public function testClearCartCodesRemovesGiftCardsFromQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithGiftCard();

        // Act
        $resultQuoteTransfer = $this->getFacade()->clearCartCodes($quoteTransfer);

        // Assert
        $this->assertCount(0, $resultQuoteTransfer->getGiftCards());
    }

    /**
     * @return void
     */
    public function testFindOperationResponseMessageReturnsMessageTransfer(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithGiftCard();

        // Act
        $messageTransfer = $this->getFacade()->findOperationResponseMessage($quoteTransfer, $this->tester::GIFT_CARD_CODE);

        // Assert
        $this->assertNotNull($messageTransfer);
    }

    /**
     * @return void
     */
    public function testSaveOrderGiftCardsShouldCreatePaymentGiftCardEntity(): void
    {
        // Arrange
        $idSalesPayment = $this->createSalesPaymentEntity();

        $anotherPaymentTransfer = new PaymentTransfer();
        $giftCardTransfer = new GiftCardTransfer();
        $giftCardTransfer->setCode(static::TEST_GIFT_CARD_CODE);
        $giftCardPaymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setGiftCard($giftCardTransfer)
            ->setAmount(100)
            ->setIdSalesPayment($idSalesPayment);

        $quoteTransfer = (new QuoteTransfer())
            ->addPayment($anotherPaymentTransfer)
            ->addPayment($giftCardPaymentTransfer);

        // Act
        $this->getFacade()->saveOrderGiftCards($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment, 1);
        $this->tester->assertPaymentGiftCardExistBySalesPaymentIdAndCode($idSalesPayment, static::TEST_GIFT_CARD_CODE);
    }

    /**
     * @return void
     */
    public function testSaveOrderGiftCardsShouldCreateSalesOrderItemGiftCardEntities(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $oneSalesOrderItemEntity = $this->tester->createSalesOrderItemForOrder(
            $saveOrderTransfer->getIdSalesOrder(),
            ['name' => 'item 1']
        );
        $twoSalesOrderItemEntity = $this->tester->createSalesOrderItemForOrder(
            $saveOrderTransfer->getIdSalesOrder(),
            ['name' => 'item 2']
        );

        $oneGiftCardItemTransfer = new ItemTransfer();
        $oneGiftCardItemTransfer->setIdSalesOrderItem($oneSalesOrderItemEntity->getIdSalesOrderItem());
        $oneGiftCardItemTransfer->setGiftCardMetadata(new GiftCardMetadataTransfer());
        $oneGiftCardItemTransfer->getGiftCardMetadata()
            ->setIsGiftCard(true)
            ->setAbstractConfiguration(new GiftCardAbstractProductConfigurationTransfer());
        $twoGiftCardItemTransfer = new ItemTransfer();
        $twoGiftCardItemTransfer->setIdSalesOrderItem($twoSalesOrderItemEntity->getIdSalesOrderItem());
        $twoGiftCardItemTransfer->setGiftCardMetadata(new GiftCardMetadataTransfer());
        $twoGiftCardItemTransfer->getGiftCardMetadata()
            ->setIsGiftCard(true)
            ->setAbstractConfiguration(new GiftCardAbstractProductConfigurationTransfer());

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($oneGiftCardItemTransfer);
        $quoteTransfer->addItem($twoGiftCardItemTransfer);
        $quoteTransfer->addItem(new ItemTransfer());

        // Act
        $this->getFacade()->saveOrderGiftCards($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertSalesOrderItemGiftCardExistBySalesPaymentId($saveOrderTransfer->getIdSalesOrder(), 2);
    }

    /**
     * @return void
     */
    public function testSaveOrderGiftCardsWithZeroGiftCardAmountShouldNotCreatePaymentGiftCardEntity(): void
    {
        // Arrange
        $idSalesPayment = $this->createSalesPaymentEntity();

        $anotherPaymentTransfer = new PaymentTransfer();
        $giftCardTransfer = new GiftCardTransfer();
        $giftCardTransfer->setCode(static::TEST_GIFT_CARD_CODE);
        $giftCardPaymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setGiftCard($giftCardTransfer)
            ->setAmount(0)
            ->setIdSalesPayment($idSalesPayment);

        $quoteTransfer = (new QuoteTransfer())
            ->addPayment($anotherPaymentTransfer)
            ->addPayment($giftCardPaymentTransfer);

        // Act
        $this->getFacade()->saveOrderGiftCards($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment, 0);
    }

    /**
     * @return void
     */
    public function testSaveOrderGiftCardsWithoutGiftCardShouldNotCreatePaymentGiftCardEntity(): void
    {
        // Arrange
        $idSalesPayment = $this->createSalesPaymentEntity();

        $anotherPaymentTransfer = new PaymentTransfer();
        $giftCardPaymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setAmount(0)
            ->setIdSalesPayment($idSalesPayment);

        $quoteTransfer = (new QuoteTransfer())
            ->addPayment($anotherPaymentTransfer)
            ->addPayment($giftCardPaymentTransfer);

        // Act
        $this->getFacade()->saveOrderGiftCards($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment, 0);
    }

    /**
     * @return void
     */
    public function testSaveOrderGiftCardsExecutePluginStack(): void
    {
        // Arrange
        $anotherPaymentTransfer = new PaymentTransfer();
        $giftCardTransfer = new GiftCardTransfer();
        $giftCardTransfer->setCode(static::TEST_GIFT_CARD_CODE);
        $giftCardPaymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setGiftCard($giftCardTransfer)
            ->setAmount(100)
            ->setIdSalesPayment($this->createSalesPaymentEntity());

        $quoteTransfer = (new QuoteTransfer())
            ->addPayment($anotherPaymentTransfer)
            ->addPayment($giftCardPaymentTransfer);

        $giftCardPaymentSaverPluginMock = $this->getMockBuilder(GiftCardPaymentSaverPluginInterface::class)
            ->getMock();
        $giftCardPaymentSaverPluginMock->expects($this->once())
            ->method('savePayment');

        $this->tester->setDependency(
            GiftCardDependencyProvider::GIFT_CARD_PAYMENT_SAVER_PLUGINS,
            [
                $giftCardPaymentSaverPluginMock,
            ]
        );

        // Act
        $this->getFacade()->saveOrderGiftCards($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return int
     */
    protected function createSalesPaymentEntity(): int
    {
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $salesPaymentTransfer = (new SalesPaymentTransfer())
            ->setPaymentProvider('Test provider')
            ->setPaymentMethod('Test method')
            ->setAmount(100)
            ->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder());
        $salesPaymentEntity = $this->tester->haveSalesPaymentEntity($salesPaymentTransfer);

        return $salesPaymentEntity->getIdSalesPayment();
    }

    /**
     * @return array
     */
    protected function getDataWithOnlyGiftCardItems(): array
    {
        $giftCardMetadataBuilder = new GiftCardMetadataBuilder([GiftCardMetadataTransfer::IS_GIFT_CARD => true]);

        $availableShipmentMethodsBuilder = (new ShipmentMethodsBuilder())
            ->withMethod(new ShipmentMethodBuilder([ShipmentMethodTransfer::NAME => 'NoShipment']))
            ->withAnotherMethod(new ShipmentMethodBuilder([ShipmentMethodTransfer::NAME => 'Test method 1']))
            ->withAnotherMethod(new ShipmentMethodBuilder([ShipmentMethodTransfer::NAME => 'Test method 2']));

        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem((new ItemBuilder())->withGiftCardMetadata($giftCardMetadataBuilder))
            ->withAnotherItem((new ItemBuilder())->withGiftCardMetadata($giftCardMetadataBuilder))
            ->withAnotherItem((new ItemBuilder())->withGiftCardMetadata($giftCardMetadataBuilder))
            ->withAvailableShipmentMethods($availableShipmentMethodsBuilder)
            ->build();

        return [$shipmentGroupTransfer, ['NoShipment'], ['Test method 1', 'Test method 2']];
    }

    /**
     * @return array
     */
    public function getDataWithNotOnlyGiftCardItems(): array
    {
        $giftCardMetadataBuilder = (new GiftCardMetadataBuilder([GiftCardMetadataTransfer::IS_GIFT_CARD => true]));

        $availableShipmentMethodsBuilder = (new ShipmentMethodsBuilder())
            ->withMethod(new ShipmentMethodBuilder([ShipmentMethodTransfer::NAME => 'NoShipment']))
            ->withAnotherMethod(new ShipmentMethodBuilder([ShipmentMethodTransfer::NAME => 'Test method 1']))
            ->withAnotherMethod(new ShipmentMethodBuilder([ShipmentMethodTransfer::NAME => 'Test method 2']));

        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem((new ItemBuilder())->withGiftCardMetadata($giftCardMetadataBuilder))
            ->withAnotherItem(new ItemBuilder())
            ->withAnotherItem(new ItemBuilder())
            ->withAvailableShipmentMethods($availableShipmentMethodsBuilder)
            ->build();

        return [$shipmentGroupTransfer, ['Test method 1', 'Test method 2'], ['NoShipment']];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected function createGiftCardConfigMock(): GiftCardConfig
    {
        $mock = $this->getMockBuilder(GiftCardConfig::class)->getMock();
        $mock->method('getGiftCardOnlyShipmentMethods')->willReturn(['NoShipment']);

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory
     */
    protected function createGiftCardBusinessFactoryMock(): GiftCardBusinessFactory
    {
        return $this->getMockBuilder(GiftCardBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface
     */
    protected function getFacadeWithMockedConfig(): GiftCardFacadeInterface
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCard\GiftCardConfig $configMock */
        $configMock = $this->createGiftCardConfigMock();
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory $businessFactoryMock */
        $businessFactoryMock = $this->createGiftCardBusinessFactoryMock();
        $businessFactoryMock->setConfig($configMock);

        /** @var \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($businessFactoryMock);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
