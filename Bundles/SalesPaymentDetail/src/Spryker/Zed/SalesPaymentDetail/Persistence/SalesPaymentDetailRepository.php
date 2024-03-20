<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Persistence;

use Generated\Shared\Transfer\SalesPaymentDetailTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailPersistenceFactory getFactory()
 */
class SalesPaymentDetailRepository extends AbstractRepository implements SalesPaymentDetailRepositoryInterface
{
    /**
     * @param string $entityReference
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer|null
     */
    public function findByEntityReference(string $entityReference): ?SalesPaymentDetailTransfer
    {
        $salesPaymentDetailEntity = $this->getFactory()
            ->createSalesPaymentDetailQuery()
            ->filterByEntityReference($entityReference)
            ->findOne();

        if ($salesPaymentDetailEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createSalesPaymentDetailMapper()
            ->mapSalesPaymentDetailEntityToSalesPaymentDetailTransfer($salesPaymentDetailEntity, new SalesPaymentDetailTransfer());
    }
}
