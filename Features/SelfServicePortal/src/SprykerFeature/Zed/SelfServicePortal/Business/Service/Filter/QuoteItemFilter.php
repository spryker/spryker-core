<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class QuoteItemFilter implements QuoteItemFilterInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
    protected const MESSAGE_INFO_SERVICE_WITHOUT_SHIPMENT_TYPE_DELETED = 'ssp-service-management.info.service-without-shipment-type.removed';

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected SelfServicePortalConfig $config;

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    protected MessengerFacadeInterface $messengerFacade;

    public function __construct(
        SelfServicePortalConfig $config,
        MessengerFacadeInterface $messengerFacade
    ) {
        $this->config = $config;
        $this->messengerFacade = $messengerFacade;
    }

    public function filterOutServicesWithoutShipmentTypes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getItems()->count()) {
            return $quoteTransfer;
        }

        $filteredItems = new ArrayObject();
        $messageTransfersIndexedBySku = [];

        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            if ($this->isFiltrationNeeded($itemTransfer)) {
                $sku = $itemTransfer->getSkuOrFail();
                $messageTransfersIndexedBySku = $this->addFilterMessage($sku, $messageTransfersIndexedBySku);

                continue;
            }

            $filteredItems->offsetSet($key, $itemTransfer);
        }

        return $quoteTransfer->setItems($filteredItems);
    }

    protected function isFiltrationNeeded(ItemTransfer $itemTransfer): bool
    {
        $serviceProductClassName = $this->config->getServiceProductClassName();
        $isServiceItem = false;

        foreach ($itemTransfer->getProductClasses() as $productClassTransfer) {
            /** @var \Generated\Shared\Transfer\ProductClassTransfer $productClassTransfer */
            if ($productClassTransfer->getNameOrFail() === $serviceProductClassName) {
                $isServiceItem = true;

                break;
            }
        }

        if (!$isServiceItem) {
            return false;
        }

        return $itemTransfer->getShipmentType() === null;
    }

    /**
     * @param string $sku
     * @param array<string, \Generated\Shared\Transfer\MessageTransfer> $messageTransfersIndexedBySku
     *
     * @return array<string, \Generated\Shared\Transfer\MessageTransfer>
     */
    protected function addFilterMessage(string $sku, array $messageTransfersIndexedBySku): array
    {
        if (isset($messageTransfersIndexedBySku[$sku])) {
            return $messageTransfersIndexedBySku;
        }

        $messageTransfersIndexedBySku[$sku] = (new MessageTransfer())
            ->setValue(static::MESSAGE_INFO_SERVICE_WITHOUT_SHIPMENT_TYPE_DELETED)
            ->setParameters([
                static::MESSAGE_PARAM_SKU => $sku,
            ]);

        $this->messengerFacade->addErrorMessage($messageTransfersIndexedBySku[$sku]);

        return $messageTransfersIndexedBySku;
    }
}
