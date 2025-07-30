<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantFile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFile;
use Propel\Runtime\Collection\Collection;

interface MerchantFileMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     * @param \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile $spyMerchantFile
     *
     * @return \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile
     */
    public function mapTransferToEntity(
        MerchantFileTransfer $merchantFileTransfer,
        SpyMerchantFile $spyMerchantFile
    ): SpyMerchantFile;

    /**
     * @param \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile $spyMerchantFile
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function mapEntityToTransfer(
        SpyMerchantFile $spyMerchantFile,
        MerchantFileTransfer $merchantFileTransfer
    ): MerchantFileTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantFile\Persistence\SpyMerchantFile> $spyMerchantFileCollection
     * @param \Generated\Shared\Transfer\MerchantFileCollectionTransfer $merchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function mapEntityCollectionToTransfer(
        Collection $spyMerchantFileCollection,
        MerchantFileCollectionTransfer $merchantFileCollectionTransfer
    ): MerchantFileCollectionTransfer;
}
