<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipmentGui\Communication\ShipmentGui;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin\ShipmentOrderItemTemplatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantShipmentGui\Communication\MerchantShipmentGuiCommunicationFactory getFactory()
 */
class MerchantShipmentOrderItemTemplatePlugin extends AbstractPlugin implements ShipmentOrderItemTemplatePluginInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_NAME = 'merchantName';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return '@MerchantShipmentGui/Shipment/index.twig';
    }

    /**
     * {@inheritDoc}
     *  - Returns merchant name data.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<mixed>
     */
    public function getTemplateData(ArrayObject $itemTransfers): array
    {
        $merchantTemplateData = [];
        $merchantTemplateData[static::MERCHANT_NAME] = [];

        $merchantReferences = array_map(
            function (ItemTransfer $itemTransfer) {
                return $itemTransfer->getMerchantReference();
            },
            $itemTransfers->getArrayCopy(),
        );
        $merchantReferences = array_unique(array_filter($merchantReferences));

        $merchantCollectionTransfer = $this->getFactory()->getMerchantFacade()->get(
            (new MerchantCriteriaTransfer())->setMerchantReferences($merchantReferences),
        );

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantTemplateData[static::MERCHANT_NAME][$merchantTransfer->getMerchantReference()] = $merchantTransfer->getName();
        }

        return $merchantTemplateData;
    }
}
