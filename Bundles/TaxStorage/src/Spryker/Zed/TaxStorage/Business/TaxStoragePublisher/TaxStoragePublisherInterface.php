<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;

interface TaxStoragePublisherInterface
{
    /**
     * @param array<int> $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void;

    /**
     * @param array<int> $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void;
}
