<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 */
abstract class AbstractCmsPageMessageBrokerPublisherPlugin extends AbstractPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $chunkSize = max(1, $this->getConfig()->getCmsPageMessageBrokerChunkSize());
        $chunks = array_chunk($eventEntityTransfers, $chunkSize);

        foreach ($chunks as $chunk) {
            $this->processEventChunk($chunk);
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return array<int>
     */
    protected function getIds(array $eventEntityTransfers): array
    {
        return array_map(
            fn ($eventEntityTransfer) => $eventEntityTransfer->getId(),
            $eventEntityTransfers,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    abstract protected function processEventChunk(array $eventEntityTransfers): void;
}
