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
class TaxStorageFacade extends AbstractFacade
{
    public function publishByTaxSetIds(array $taxSetIds): void
    {
    }

    public function publishByTaxRateIds(array $taxRateIds): void
    {

    }

    public function unpublishByTaxSetIds(array $taxSetIds): void
    {

    }
}