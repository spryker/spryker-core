<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Tax\Persistence\TaxPersistenceFactory getFactory()
 */
class TaxRepository extends AbstractRepository implements TaxRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function isTaxSetNameUnique(string $name): bool
    {
        $query = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByName($name);

        return !$query->exists();
    }

    /**
     * @param string $name
     * @param int $idTaxSet
     *
     * @return bool
     */
    public function isTaxSetNameAndIdUnique(string $name, int $idTaxSet): bool
    {
        $query = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByName($name)
            ->filterByIdTaxSet($idTaxSet, Criteria::NOT_EQUAL);

        return !$query->exists();
    }
}
