<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

interface TaxStorageRepositoryInterface
{
    /**
     * @param array $taxRateIds
     *
     * @return array
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array;

    /**
     * @param array $taxSetIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Tax\Persistence\SpyTaxSet[]
     */
    public function findTaxSetsByIds(array $taxSetIds): iterable;

    /**
     * @param array $taxSetIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\TaxStorage\Persistence\Base\SpyTaxSetStorage[]
     */
    public function findTaxSetStoragesByIds(array $taxSetIds): iterable;

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\TaxStorage\Persistence\Base\SpyTaxSetStorage[]
     */
    public function findAllTaxSetSorage(): iterable;
}
