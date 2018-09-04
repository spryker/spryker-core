<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiPersistenceFactory getFactory()
 */
class CustomersRestApiEntityManager extends AbstractEntityManager implements CustomersRestApiEntityManagerInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @return void
     */
    public function updateAddressesWithoutUuid(): void
    {
        $addressesQuery = $this->getFactory()->getAddressesPropelQuery();

        do {
            $addresses = $addressesQuery
                ->filterByUuid(null, Criteria::ISNULL)
                ->limit(static::BATCH_SIZE);
            foreach ($addresses as $address) {
                $address->save();
            }
        } while ($addresses->count());
    }
}
