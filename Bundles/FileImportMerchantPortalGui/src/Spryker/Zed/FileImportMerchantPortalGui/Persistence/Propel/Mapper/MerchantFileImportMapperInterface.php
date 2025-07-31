<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantFileImportCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport;
use Propel\Runtime\Collection\Collection;

interface MerchantFileImportMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport $merchantFileImportEntity
     *
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport
     */
    public function mapTransferToEntity(
        MerchantFileImportTransfer $merchantFileImportTransfer,
        SpyMerchantFileImport $merchantFileImportEntity
    ): SpyMerchantFileImport;

    /**
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport $merchantFileImportEntity
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function mapEntityToTransfer(
        SpyMerchantFileImport $merchantFileImportEntity,
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport> $merchantFileImportCollection
     * @param \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function mapEntityCollectionToTransfer(
        Collection $merchantFileImportCollection,
        MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
    ): MerchantFileImportCollectionTransfer;
}
