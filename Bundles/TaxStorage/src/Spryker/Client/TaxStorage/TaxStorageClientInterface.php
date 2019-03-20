<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxStorage;

use Generated\Shared\Transfer\TaxSetStorageTransfer;

interface TaxStorageClientInterface
{
    /**
     * Specification:
     * - Get TaxSet with connected TaxRates from Redis.
     *
     * @api
     *
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer|null
     */
    public function findTaxSetCollectionStorage(int $idTaxSet): ?TaxSetStorageTransfer;
}
