<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\MerchantPublisherConfigTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

abstract class AbstractMerchantMessageBrokerPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return \Generated\Shared\Transfer\MerchantPublisherConfigTransfer
     */
    protected function getMerchantPublisherConfigTransfer(
        array $eventEntityTransfers,
        string $eventName
    ): MerchantPublisherConfigTransfer {
        $merchantIds = [];

        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $merchantIds[] = $eventEntityTransfer->getIdOrFail();
        }

        return (new MerchantPublisherConfigTransfer())
            ->setMerchantIds(array_unique($merchantIds))
            ->setEventName($eventName);
    }
}
