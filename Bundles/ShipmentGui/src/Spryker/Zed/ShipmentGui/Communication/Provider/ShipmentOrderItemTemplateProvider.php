<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Provider;

use ArrayObject;

class ShipmentOrderItemTemplateProvider implements ShipmentOrderItemTemplateProviderInterface
{
    /**
     * @var array<\Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin\ShipmentOrderItemTemplatePluginInterface>
     */
    protected $shipmentOrderItemTemplatePlugins;

    /**
     * @param array<\Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin\ShipmentOrderItemTemplatePluginInterface> $shipmentOrderItemTemplatePlugins
     */
    public function __construct(array $shipmentOrderItemTemplatePlugins)
    {
        $this->shipmentOrderItemTemplatePlugins = $shipmentOrderItemTemplatePlugins;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, mixed>
     */
    public function provide(ArrayObject $itemTransfers): array
    {
        $templateData = [];

        foreach ($this->shipmentOrderItemTemplatePlugins as $shipmentOrderItemTemplatePlugin) {
            $templateData[$shipmentOrderItemTemplatePlugin->getTemplatePath()] = $shipmentOrderItemTemplatePlugin->getTemplateData($itemTransfers);
        }

        return $templateData;
    }
}
