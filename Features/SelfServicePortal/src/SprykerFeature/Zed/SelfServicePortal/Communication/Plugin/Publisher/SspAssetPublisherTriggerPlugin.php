<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class SspAssetPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves SSP assets by offset and limit.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())->setPagination($paginationTransfer);

        return $this->getFacade()
            ->getSspAssetCollection($sspAssetCriteriaTransfer)
            ->getSspAssets()
            ->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return SelfServicePortalConfig::SSP_ASSET_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpySspAssetTableMap::COL_ID_SSP_ASSET;
    }
}
