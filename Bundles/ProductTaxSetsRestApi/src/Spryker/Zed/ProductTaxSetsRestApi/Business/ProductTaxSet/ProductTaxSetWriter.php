<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductTaxSetsRestApi\Business\ProductTaxSet;

use Spryker\Zed\ProductTaxSetsRestApi\Persistence\ProductTaxSetsRestApiEntityManagerInterface;

class ProductTaxSetWriter implements ProductTaxSetWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductTaxSetsRestApi\Persistence\ProductTaxSetsRestApiEntityManagerInterface
     */
    protected $taxSetEntityManager;

    /**
     * @param \Spryker\Zed\ProductTaxSetsRestApi\Persistence\ProductTaxSetsRestApiEntityManagerInterface $taxSetEntityManager
     */
    public function __construct(ProductTaxSetsRestApiEntityManagerInterface $taxSetEntityManager)
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
