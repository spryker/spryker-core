<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\ProductPublisherConfigTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

abstract class AbstractProductMessageBrokerPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return \Generated\Shared\Transfer\ProductPublisherConfigTransfer
     */
    protected function getProductPublisherConfigTransfer(
        array $eventEntityTransfers,
        string $eventName
    ): ProductPublisherConfigTransfer {
        $productIds = [];

        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $productIds[] = $eventEntityTransfer->getId();
        }

        return (new ProductPublisherConfigTransfer())
            ->setProductIds(array_unique($productIds))
            ->setEventName($eventName);
    }
}
