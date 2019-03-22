<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business;

/**
 * These methods should call respective methods in the publisher business model
 */
interface TaxStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all TaxSets with related TaxRates by the given $taxSetIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void;

    /**
     * Specification:
     * - Finds and deletes TaxSet storage entities with the given $taxSetIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void;

    /**
     * Specification:
     * - Queries all TaxSets with related TaxRates by the given $taxRateIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void;
}
