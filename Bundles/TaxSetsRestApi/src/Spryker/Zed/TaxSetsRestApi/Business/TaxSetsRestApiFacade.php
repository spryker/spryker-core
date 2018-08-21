<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\TaxSetsRestApi\Business\TaxSetsRestApiBusinessFactory getFactory()
 */
class TaxSetsRestApiFacade extends AbstractFacade implements TaxSetsRestApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function updateTaxSetsWithoutUuid(): void
    {
        $this->getFactory()
            ->createTaxSetWriter()
            ->updateTaxSetsWithoutUuid();
    }
}
