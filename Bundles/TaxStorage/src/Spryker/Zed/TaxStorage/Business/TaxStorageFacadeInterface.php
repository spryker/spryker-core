<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * These methods should call respective methods in the publisher business model
 */
interface TaxStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void;

    /**
     * @api
     *
     * @param array $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void;

    /**
     * @api
     *
     * @param array $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void;
}
