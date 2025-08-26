<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetStorageConditionsTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;

class SspAssetStorageReader implements SspAssetStorageReaderInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_ASSET_REFERENCE = 'reference';

    public function __construct(protected SelfServicePortalClientInterface $selfServicePortalClient)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $assetReference
     *
     * @return array<string, mixed>|null
     */
    public function getSspAssetDataByReference(CompanyUserTransfer $companyUserTransfer, string $assetReference): ?array
    {
        $sspAssetCollection = $this->selfServicePortalClient->getSspAssetStorageCollection(
            (new SspAssetStorageCriteriaTransfer())
                ->setCompanyUser($companyUserTransfer)
                ->setSspAssetStorageConditions(
                    (new SspAssetStorageConditionsTransfer())
                        ->setReferences([$assetReference]),
                ),
        );

        if ($sspAssetCollection->getSspAssetStorages()->count() === 0) {
            return null;
        }

        $sspAssetStorage = $sspAssetCollection->getSspAssetStorages()->getIterator()->current();

        if (!$sspAssetStorage) {
            return null;
        }

        $sspAssetStorageData = $sspAssetStorage->toArray();
        $sspAssetStorageData[static::PARAMETER_ASSET_REFERENCE] = $assetReference;

        return $sspAssetStorageData;
    }
}
