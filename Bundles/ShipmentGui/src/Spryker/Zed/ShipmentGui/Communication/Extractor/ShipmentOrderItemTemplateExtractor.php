<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Extractor;

use ArrayObject;

class ShipmentOrderItemTemplateExtractor implements ShipmentOrderItemTemplateExtractorInterface
{
    /**
     * @var \Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin\ShipmentOrderItemTemplatePluginInterface[]
     */
    protected $shipmentOrderItemTemplatePlugins;

    /**
     * @param \Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin\ShipmentOrderItemTemplatePluginInterface[] $shipmentOrderItemTemplatePlugins
     */
    public function __construct(array $shipmentOrderItemTemplatePlugins)
    {
        $this->shipmentOrderItemTemplatePlugins = $shipmentOrderItemTemplatePlugins;
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function getAdditionalData(ArrayObject $items): array
    {
        $additionalData = [];

        foreach ($this->shipmentOrderItemTemplatePlugins as $additionalMerchantPlugin) {
            $additionalData[$additionalMerchantPlugin->getTemplatePath()] = $additionalMerchantPlugin->getAdditionData($items);
        }

        return $additionalData;
    }
}
