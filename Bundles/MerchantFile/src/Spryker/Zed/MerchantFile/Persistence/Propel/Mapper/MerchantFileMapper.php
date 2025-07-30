<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFile;
use Propel\Runtime\Collection\Collection;

class MerchantFileMapper implements MerchantFileMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     * @param \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile $merchantFileEntity
     *
     * @return \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile
     */
    public function mapTransferToEntity(
        MerchantFileTransfer $merchantFileTransfer,
        SpyMerchantFile $merchantFileEntity
    ): SpyMerchantFile {
        return $merchantFileEntity->fromArray($merchantFileTransfer->toArray());
    }

    /**
     * @param \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile $merchantFileEntity
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function mapEntityToTransfer(
        SpyMerchantFile $merchantFileEntity,
        MerchantFileTransfer $merchantFileTransfer
    ): MerchantFileTransfer {
        return $merchantFileTransfer->fromArray($merchantFileEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantFile\Persistence\SpyMerchantFile> $merchantFileCollection
     * @param \Generated\Shared\Transfer\MerchantFileCollectionTransfer $merchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function mapEntityCollectionToTransfer(
        Collection $merchantFileCollection,
        MerchantFileCollectionTransfer $merchantFileCollectionTransfer
    ): MerchantFileCollectionTransfer {
        foreach ($merchantFileCollection as $merchantFileEntity) {
            $merchantFileTransfer = $this->mapEntityToTransfer(
                $merchantFileEntity,
                new MerchantFileTransfer(),
            );
            $merchantFileCollectionTransfer->addMerchantFile($merchantFileTransfer);
        }

        return $merchantFileCollectionTransfer;
    }
}
