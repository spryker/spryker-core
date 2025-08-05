<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\AddressFormCheckerInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SingleAddressPerShipmentTypeAddressStepForm;
use Symfony\Component\Form\FormEvent;

class SingleAddressPerShipmentTypePreSubmitHandler implements SingleAddressPerShipmentTypePreSubmitHandlerInterface
{
    /**
     * @var string
     */
    protected const FIELD_SHIPPING_ADDRESS = 'shippingAddress';

    /**
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPE = 'shipmentType';

    /**
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPE_KEY = 'key';

    /**
     * @var string
     */
    protected const EXTRA_FIELD_SKIP_VALIDATION = 'skip_validation';

    public function __construct(
        protected AddressFormCheckerInterface $addressFormChecker
    ) {
    }

    public function handlePreSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        if (!is_array($data)) {
            return;
        }

        $form = $event->getForm();

        if (!$this->shouldProcessEvent($data)) {
            return;
        }

        $currentShipmentTypeKey = $data[static::FIELD_SHIPMENT_TYPE][static::FIELD_SHIPMENT_TYPE_KEY];

        $checkoutMultiShippingAddressesForm = $form->getParent();
        if (!$checkoutMultiShippingAddressesForm) {
            return;
        }

        foreach ($checkoutMultiShippingAddressesForm->all() as $checkoutAddressForm) {
            if ($checkoutAddressForm === $form) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $checkoutAddressForm->getData();

            if (!$this->isSameShipmentType($itemTransfer, $currentShipmentTypeKey)) {
                continue;
            }

            if (!$itemTransfer->getIsSingleAddressPerShipmentType()) {
                continue;
            }

            $data = $this->copyAddressFromSiblingForm($data, $itemTransfer);
            $event->setData($data);

            return;
        }
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return bool
     */
    protected function shouldProcessEvent(array $data): bool
    {
        if (isset($data[SingleAddressPerShipmentTypeAddressStepForm::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE])) {
            return false;
        }

        if (!isset($data[static::FIELD_SHIPPING_ADDRESS])) {
            return false;
        }

        if (!isset($data[static::FIELD_SHIPMENT_TYPE][static::FIELD_SHIPMENT_TYPE_KEY])) {
            return false;
        }

        $shipmentTypeKey = $data[static::FIELD_SHIPMENT_TYPE][static::FIELD_SHIPMENT_TYPE_KEY];

        return $this->addressFormChecker->isApplicableShipmentType($shipmentTypeKey);
    }

    protected function isSameShipmentType(ItemTransfer $itemTransfer, string $currentShipmentTypeKey): bool
    {
        return $itemTransfer->getShipmentType()?->getKey() === $currentShipmentTypeKey;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    protected function copyAddressFromSiblingForm(array $data, ItemTransfer $itemTransfer): array
    {
        $data[static::FIELD_SHIPPING_ADDRESS] = $itemTransfer->getShipmentOrFail()->getShippingAddressOrFail()->toArray();
        $data[static::FIELD_SHIPPING_ADDRESS][static::EXTRA_FIELD_SKIP_VALIDATION] = true;

        return $data;
    }
}
