<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @package Spryker\Zed\Tax\Persistence
 * @method \Spryker\Zed\Tax\Persistence\TaxPersistenceFactory getFactory()
 */
class TaxRepository extends AbstractRepository implements TaxRepositoryInterface
{
    /**
     * @param string $name
     * @param int|null $idTaxSet
     *
     * @return bool
     */
    public function isTaxSetNameUnique(string $name, ?int $idTaxSet = null): bool
    {
        $query = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByName($name);

        if ($idTaxSet) {
            $query = $query->filterByIdTaxSet($idTaxSet, Criteria::NOT_EQUAL);
        }

        return !$query->exists();
    }
}
