<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Search;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 */
class SspAssetToModelWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes SSP asset data by `SpySspAssetToSspModel` entity events.
     * - Extracts SSP asset IDs from the `$eventEntityTransfers` created by SSP asset to model entity events.
     * - Updates entities from `spy_ssp_asset_search` with actual data from obtained SSP Assets.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getBusinessFactory()->createSspAssetSearchWriter()->writeCollectionBySspAssetToModelEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE,
        ];
    }
}
