<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Persistence;

use Generated\Shared\Transfer\SalesPaymentDetailTransfer;

interface SalesPaymentDetailRepositoryInterface
{
    /**
     * @param string $entityReference
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer|null
     */
    public function findByEntityReference(string $entityReference): ?SalesPaymentDetailTransfer;
}
