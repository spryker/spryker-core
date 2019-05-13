<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

interface TaxStorageRepositoryInterface
{
    /**
     * @param int[] $taxRateIds
     *
     * @return int[]
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array;

    /**
     * @param int[] $taxSetIds
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer[]
     */
    public function findTaxSetsByIds(array $taxSetIds): array;

    /**
     * @param int[] $taxSetIds
     *
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findTaxSetStoragesByIdTaxSetsIndexedByFkTaxSet(array $taxSetIds): array;

    /**
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findAllTaxSetStorages(): array;
}
