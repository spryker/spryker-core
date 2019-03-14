<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;


interface TaxStoragePublisherInterface
{
    /**
     * @param array $taxSetIds
     */
    public function publishByTaxSetIds(array $taxSetIds): void;

    /**
     * Implement logic that fetches data from database using repository, prepares data and stores it to database using
     * entity manager
     *
     * @param array $taxRateIds
     */
    public function publishByTaxRateIds(array $taxRateIds): void;

    /**
     * Implement logic that deletes data from database using entity manager
     *
     * @param array $taxSetIds
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void;

}