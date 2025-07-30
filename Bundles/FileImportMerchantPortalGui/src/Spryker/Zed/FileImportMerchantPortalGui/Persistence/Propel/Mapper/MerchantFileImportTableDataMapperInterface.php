<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantFileImportCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport;
use Propel\Runtime\Util\PropelModelPager;

interface MerchantFileImportTableDataMapperInterface
{
    /**
     * @param array<\Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport> $merchantFileImportEntities
     * @param \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function mapMerchantFileImportEntityArrayToCollectionTransfer(
        array $merchantFileImportEntities,
        MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
    ): MerchantFileImportCollectionTransfer;

    /**
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport $merchantFileImportEntity
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function mapMerchantFileImportEntityToTransfer(
        SpyMerchantFileImport $merchantFileImportEntity,
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportTransfer;

    /**
     * @param \Propel\Runtime\Util\PropelModelPager $propelModelPager
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    public function mapPropelModelPagerToPaginationTransfer(
        PropelModelPager $propelModelPager,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer;
}
