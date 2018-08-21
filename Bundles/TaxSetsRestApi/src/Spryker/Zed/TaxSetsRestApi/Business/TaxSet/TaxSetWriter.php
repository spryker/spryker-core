<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Business\TaxSet;

use Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiEntityManagerInterface;

class TaxSetWriter implements TaxSetWriterInterface
{
    /**
     * @var \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiEntityManagerInterface
     */
    protected $taxSetEntityManager;

    /**
     * @param \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiEntityManagerInterface $taxSetEntityManager
     */
    public function __construct(TaxSetsRestApiEntityManagerInterface $taxSetEntityManager)
    {
        $this->taxSetEntityManager = $taxSetEntityManager;
    }

    /**
     * @return void
     */
    public function updateTaxSetsWithoutUuid(): void
    {
        $this->taxSetEntityManager->updateTaxSetsWithoutUuid();
    }
}
