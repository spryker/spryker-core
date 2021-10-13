<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface CompanyUserStorageRepositoryInterface
{
    /**
     * @deprecated Use {@link getSynchronizationDataTransfersByFilterAndCompanyUserIds()} instead.
     *
     * @see \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndCompanyUserIds()
     *
     * @param array $companyUserIds
     *
     * @return array<\Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage>
     */
    public function findCompanyUserStorageEntities(array $companyUserIds): array;

    /**
     * @deprecated Use {@link getSynchronizationDataTransfersByFilterAndCompanyUserIds()} instead.
     *
     * @see \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndCompanyUserIds()
     *
     * @return array<\Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage>
     */
    public function findAllCompanyUserStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $companyUserIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndCompanyUserIds(FilterTransfer $filterTransfer, array $companyUserIds = []): array;
}
