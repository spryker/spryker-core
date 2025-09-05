<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetStorageConditionsTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;

class SspAssetStorageReader implements SspAssetStorageReaderInterface
{
    public function __construct(protected SelfServicePortalClientInterface $selfServicePortalClient)
    {
    }

    public function findSspAssetStorageByReference(CompanyUserTransfer $companyUserTransfer, string $assetReference): ?SspAssetStorageTransfer
    {
        $sspAssetStorageCollectionTransfer = $this->selfServicePortalClient->getSspAssetStorageCollection(
            (new SspAssetStorageCriteriaTransfer())
                ->setCompanyUser($companyUserTransfer)
                ->setSspAssetStorageConditions(
                    (new SspAssetStorageConditionsTransfer())
                        ->setReferences([$assetReference]),
                ),
        );

        if ($sspAssetStorageCollectionTransfer->getSspAssetStorages()->count() === 0) {
            return null;
        }

        return $sspAssetStorageCollectionTransfer->getSspAssetStorages()->getIterator()->current();
    }
}
