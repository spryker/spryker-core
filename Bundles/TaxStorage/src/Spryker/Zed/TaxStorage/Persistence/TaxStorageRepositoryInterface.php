<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

interface TaxStorageRepositoryInterface
{
    /**
     * finds to which tax sets assigned changed tax rates uses SpyTaxSetTaxQuery
     *
     * @param array $taxRateIds
     *
     * @return array
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array;

    /**
     *
     * @param array $taxSetIds
     *
     * @return \Spryker\Zed\TaxStorage\Persistence\SpyTaxSetStorag[]
     */
    public function findTaxSetSorageEntities(array $taxSetIds): array;

    /**
     * findAllTaxSetSorageEntities
     */
    public function findAllTaxSetSorageEntities();
}
