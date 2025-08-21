<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Mapper\SspAssetSearchMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspAssetSearchReader implements SspAssetSearchReaderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $repository,
        protected SspAssetSearchMapperInterface $sspAssetSearchMapper
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $sspAssetIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersBySspAssetIds(
        FilterTransfer $filterTransfer,
        array $sspAssetIds = []
    ): array {
        $sspAssetSearchEntities = $this->repository->getSspAssetSearchEntitiesByIds($filterTransfer, $sspAssetIds);

        return $this->sspAssetSearchMapper->mapSspAssetSearchEntitiesToSynchronizationDataTransfers($sspAssetSearchEntities);
    }
}
