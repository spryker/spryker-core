<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfPersistenceFactory getFactory()
 */
class BusinessOnBehalfRepository extends AbstractRepository implements BusinessOnBehalfRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isOnBehalfByCustomerId(int $idCustomer): bool
    {
        $query = $this->getFactory()->getCompanyUserQuery();
        $query->filterByFkCustomer($idCustomer);

        return ($query->count() > 1);
    }
}
