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
use Generated\Shared\Transfer\GiftCardMetadataTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory;
use Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface;
use Spryker\Zed\GiftCard\GiftCardConfig;

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
    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindByIdShouldReturnTransferObjectForExistingGiftCard()
    {
        $giftCardTransfer = $this->tester->haveGiftCard(['attributes' => []]);

        $foundGiftCardTransfer = $this->getFacade()->findById($giftCardTransfer->getIdGiftCard());

        $this->assertNotNull($foundGiftCardTransfer);
        $this->assertSame($giftCardTransfer->getIdGiftCard(), $foundGiftCardTransfer->getIdGiftCard());
    }

    /**
     * @return void
     */
    public function testCreateShouldAssertRequiredTransferObjectFields()
    {
        $giftCardTransfer = (new GiftCardBuilder([
            'attributes' => [],
            'value' => null,
        ]))->build();

        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessageRegExp('/^Missing required property "value" for transfer/');
        $this->getFacade()->create($giftCardTransfer);
    }

    /**
     * @return void
     */
    public function testCreateShouldPersistGiftCard()
    {
        $giftCardTransfer = (new GiftCardBuilder([
            'attributes' => [],
            'idGiftCard' => null,
        ]))->build();
        $this->getFacade()->create($giftCardTransfer);

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
    ) {
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected function createGiftCardConfigMock(): GiftCardConfig
    {
        $mock = $this->getMockBuilder(GiftCardConfig::class)->getMock();
        $mock->method('getGiftCardOnlyShipmentMethods')->willReturn(['NoShipment']);

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory
     */
    protected function createGiftCardBusinessFactoryMock(): GiftCardBusinessFactory
    {
        return $this->getMockBuilder(GiftCardBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface
     */
    protected function getFacadeWithMockedConfig(): GiftCardFacadeInterface
    {
        $configMock = $this->createGiftCardConfigMock();
        $businessFactoryMock = $this->createGiftCardBusinessFactoryMock();
        $businessFactoryMock->setConfig($configMock);

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
