<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use \ArrayObject;

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
     * @return \ArrayObject
     */
    public function findAllTaxSetSorageEntities(): ArrayObject;

    /**
     * @param array $taxSetIds
     *
     * @return \ArrayObject
     */
    public function findTaxSetsByIds(array $taxSetIds): ArrayObject;

    /**
     * @param array $taxSetIds
     *
     * @return \ArrayObject
     */
    public function findTaxSetStoragesByIds(array $taxSetIds): ArrayObject;
}
