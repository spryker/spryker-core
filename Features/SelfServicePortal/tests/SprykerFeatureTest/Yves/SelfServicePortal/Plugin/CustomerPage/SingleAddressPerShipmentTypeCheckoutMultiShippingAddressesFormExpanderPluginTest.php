<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Plugin\CustomerPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerFeature\Yves\SelfServicePortal\Plugin\CustomerPage\SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\AddressFormChecker;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SingleAddressPerShipmentTypeAddressStepForm;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\SingleAddressPerShipmentTypePreSubmitHandler;
use SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * @group SprykerFeatureTest
 * @group Yves
 * @group SelfServicePortal
 * @group Plugin
 * @group CustomerPage
 * @group SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPluginTest
 */
class SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_KEY_DELIVERY = 'delivery';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_KEY_NOT_APPLICABLE = 'pickup';

    /**
     * @var \SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester
     */
    protected SelfServicePortalYvesTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldReturnSameFormBuilderInstance(): void
    {
        // Arrange
        $formBuilderMock = $this->createFormBuilderMock();

        $plugin = new SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin();

        // Act
        $result = $plugin->expand($formBuilderMock, []);

        // Assert
        $this->assertSame($formBuilderMock, $result);
    }

    /**
     * @return void
     */
    public function testExpandShouldAddCheckboxFieldForApplicableShipmentTypes(): void
    {
        // Arrange
        $formBuilderMock = $this->createFormBuilderMock();
        $itemTransfer = $this->createItemTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_KEY_DELIVERY);
        $formMock = $this->createFormMock();

        $listenerCallback = null;
        $this->mockFormBuilderPreSetDataListener($formBuilderMock, $listenerCallback);

        $formMock
            ->expects($this->once())
            ->method('add')
            ->with(
                SingleAddressPerShipmentTypeAddressStepForm::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE,
                CheckboxType::class,
            )
            ->willReturnSelf();

        $plugin = new SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin();

        // Act
        $plugin->expand($formBuilderMock, []);

        // Assert
        $this->executePreSetDataListenerCallback($listenerCallback, $formMock, $itemTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldNotAddCheckboxFieldForNonApplicableShipmentTypes(): void
    {
        // Arrange
        $formBuilderMock = $this->createFormBuilderMock();
        $itemTransfer = $this->createItemTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_KEY_NOT_APPLICABLE);
        $formMock = $this->createFormMock();

        $listenerCallback = null;
        $this->mockFormBuilderPreSetDataListener($formBuilderMock, $listenerCallback);

        $formMock
            ->expects($this->never())
            ->method('add');

        $plugin = new SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin();

        // Act
        $plugin->expand($formBuilderMock, []);

        // Assert
        $this->executePreSetDataListenerCallback($listenerCallback, $formMock, $itemTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldNotAddCheckboxFieldForBundleItems(): void
    {
        // Arrange
        $formBuilderMock = $this->createFormBuilderMock();
        $itemTransfer = $this->createItemTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_KEY_DELIVERY);
        $itemTransfer->setRelatedBundleItemIdentifier('bundle-123');
        $formMock = $this->createFormMock();

        $listenerCallback = null;
        $this->mockFormBuilderPreSetDataListener($formBuilderMock, $listenerCallback);

        $formMock
            ->expects($this->never())
            ->method('add');

        $plugin = new SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin();

        // Act
        $plugin->expand($formBuilderMock, []);

        // Assert
        $this->executePreSetDataListenerCallback($listenerCallback, $formMock, $itemTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldPopulateExistingCheckboxValueWhenItemHasSingleAddressFlag(): void
    {
        // Arrange
        $formBuilderMock = $this->createFormBuilderMock();
        $itemTransfer = $this->createItemTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_KEY_DELIVERY);
        $itemTransfer->setIsSingleAddressPerShipmentType(true);
        $formMock = $this->createFormMock();

        $listenerCallback = null;
        $this->mockFormBuilderPreSetDataListener($formBuilderMock, $listenerCallback);

        $formMock
            ->expects($this->once())
            ->method('add')
            ->with(
                SingleAddressPerShipmentTypeAddressStepForm::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE,
                CheckboxType::class,
            )
            ->willReturnSelf();

        $plugin = new SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin();

        // Act
        $plugin->expand($formBuilderMock, []);

        // Assert
        $this->executePreSetDataListenerCallback($listenerCallback, $formMock, $itemTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldAttachPreSubmitEventListener(): void
    {
        // Arrange
        $formBuilderMock = $this->createFormBuilderMock();
        $matcher = $this->exactly(2);

        $formBuilderMock
            ->expects($matcher)
            ->method('addEventListener')
            ->willReturnCallback(function (string $eventName, $listener) use ($matcher): void {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals(FormEvents::PRE_SET_DATA, $eventName),
                    2 => $this->assertEquals(FormEvents::PRE_SUBMIT, $eventName),
                };
                $this->assertInstanceOf('Closure', $listener);
            });

        $plugin = new SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin();

        // Act
        $plugin->expand($formBuilderMock, []);
    }

    /**
     * @return void
     */
    public function testExpandPreSubmitEventShouldCopyAddressFromSiblingFormWhenSingleAddressPerShipmentTypeEnabled(): void
    {
        // Arrange
        $itemTransfer = $this->createItemTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_KEY_DELIVERY);
        $itemTransfer->setIsSingleAddressPerShipmentType(true);

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setCity('Existing City');

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setShippingAddress($addressTransfer);
        $itemTransfer->setShipment($shipmentTransfer);

        $configMock = $this->createConfigMock([static::TEST_SHIPMENT_TYPE_KEY_DELIVERY]);

        $handler = new SingleAddressPerShipmentTypePreSubmitHandler(
            new AddressFormChecker($configMock),
        );

        $eventData = [
            'shippingAddress' => ['street' => 'New Street'],
            'shipmentType' => ['key' => static::TEST_SHIPMENT_TYPE_KEY_DELIVERY],
        ];

        $formMock = $this->createFormMock();
        $parentFormMock = $this->createFormMock();
        $siblingFormMock = $this->createFormMock();

        $formMock
            ->method('getParent')
            ->willReturn($parentFormMock);

        $parentFormMock
            ->method('all')
            ->willReturn([$formMock, $siblingFormMock]);

        $siblingFormMock
            ->method('getData')
            ->willReturn($itemTransfer);

        $event = new FormEvent($formMock, $eventData);

        // Act
        $handler->handlePreSubmit($event);

        // Assert
        $resultData = $event->getData();
        $expectedAddressData = $addressTransfer->toArray();
        $expectedAddressData['skip_validation'] = true;

        $this->assertEquals($expectedAddressData, $resultData['shippingAddress']);
        $this->assertTrue($resultData['shippingAddress']['skip_validation']);
    }

    /**
     * @param callable|null $listenerCallback
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormInterface $formMock
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function executePreSetDataListenerCallback(?callable $listenerCallback, FormInterface $formMock, ItemTransfer $itemTransfer): void
    {
        $this->assertNotNull($listenerCallback);
        $event = new FormEvent($formMock, $itemTransfer);
        $listenerCallback($event);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormBuilderInterface $formBuilderMock
     * @param callable|null $listenerCallback
     *
     * @return void
     */
    protected function mockFormBuilderPreSetDataListener(FormBuilderInterface $formBuilderMock, ?callable &$listenerCallback): void
    {
        $formBuilderMock->method('addEventListener')
            ->willReturnCallback(function (string $eventName, callable $callback) use (&$listenerCallback): void {
                if ($eventName === FormEvents::PRE_SET_DATA) {
                    $listenerCallback = $callback;
                }
            });
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferWithShipmentType(string $shipmentTypeKey): ItemTransfer
    {
        $shipmentTypeTransfer = new ShipmentTypeTransfer();
        $shipmentTypeTransfer->setKey($shipmentTypeKey);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipmentType($shipmentTypeTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setShippingAddress(new AddressTransfer());

        $itemTransfer->setShipment($shipmentTransfer);

        return $itemTransfer;
    }

    /**
     * @param list<string> $applicableShipmentTypes
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig
     */
    protected function createConfigMock(array $applicableShipmentTypes): SelfServicePortalConfig
    {
        $configMock = $this->getMockBuilder(SelfServicePortalConfig::class)
            ->getMock();

        $configMock
            ->method('getApplicableShipmentTypesForSingleAddressPerShipmentType')
            ->willReturn($applicableShipmentTypes);

        return $configMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormInterface
     */
    protected function createFormMock(): FormInterface
    {
        return $this->getMockBuilder(FormInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormBuilderInterface
     */
    protected function createFormBuilderMock(): FormBuilderInterface
    {
        $formBuilderMock = $this->getMockBuilder(FormBuilderInterface::class)
            ->getMock();

        $formBuilderMock
            ->method('add')
            ->willReturnSelf();

        $formBuilderMock
            ->method('has')
            ->willReturn(false);

        $formBuilderMock
            ->method('addEventListener')
            ->willReturnSelf();

        return $formBuilderMock;
    }
}
