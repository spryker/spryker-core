<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Dashboard\Reader;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;

class CmsBlockCompanyBusinessUnitStorageReader implements CmsBlockCompanyBusinessUnitStorageReaderInterface
{
    /**
     * @see \Spryker\Shared\CmsBlockStorage\CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME
     *
     * @var string
     */
    protected const CMS_BLOCK_RESOURCE_NAME = 'cms_block';

    /**
     * @var string
     */
    protected const FIELD_ID = 'id';

    public function __construct(
        protected StorageClientInterface $storageClient,
        protected SynchronizationServiceInterface $synchronizationService,
        protected StoreClientInterface $storeClient,
        protected LocaleClientInterface $localeClient
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockRequestTransfer $cmsBlockRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\CmsBlockTransfer>
     */
    public function getCmsBlocks(CmsBlockRequestTransfer $cmsBlockRequestTransfer): array
    {
        if (!$cmsBlockRequestTransfer->getCompanyUnit() || !$cmsBlockRequestTransfer->getCompanyUnitBlockName()) {
            return [];
        }

        $searchKey = $this->generateKey(
            $cmsBlockRequestTransfer->getCompanyUnitBlockNameOrFail(),
            $cmsBlockRequestTransfer->getCompanyUnitOrFail(),
            static::CMS_BLOCK_RESOURCE_NAME,
            $this->localeClient->getCurrentLocale(),
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        $blocks = $this->storageClient->get($searchKey);

        if (!$blocks) {
            return [];
        }

        return [(new CmsBlockTransfer())->setKey($blocks[static::FIELD_ID])];
    }

    protected function generateKey(
        string $reference,
        int $companyUnitId,
        string $resourceName,
        ?string $localeName = null,
        ?string $storeName = null
    ): string {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($storeName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setReference(sprintf('name:%s:company_unit:%s', $reference, $companyUnitId));

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }
}
