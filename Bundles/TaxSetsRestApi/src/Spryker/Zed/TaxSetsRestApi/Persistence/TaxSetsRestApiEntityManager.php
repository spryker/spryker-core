<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiPersistenceFactory getFactory()
 */
class TaxSetsRestApiEntityManager extends AbstractEntityManager implements TaxSetsRestApiEntityManagerInterface
{
    /**
     * @return void
     */
    public function updateTaxSetsWithoutUuid(): void
    {
        $taxSetsRepository = $this->getFactory()->getTaxSetsRestApiRepository();

        do {
            $taxSetEntities = $taxSetsRepository->getTaxSetEntitiesWithoutUuid();
            foreach ($taxSetEntities as $taxSetEntity) {
                $taxSetEntity->save();
            }
        } while ($taxSetEntities);
    }
}
