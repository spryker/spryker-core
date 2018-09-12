<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductTaxSetsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductTaxSetsRestApi\Business\ProductTaxSet\ProductTaxSetWriter;
use Spryker\Zed\ProductTaxSetsRestApi\Business\ProductTaxSet\ProductTaxSetWriterInterface;

/**
 * @method \Spryker\Zed\ProductTaxSetsRestApi\ProductTaxSetsRestApiConfig getConfig()
 * @method \Spryker\Zed\ProductTaxSetsRestApi\Persistence\ProductTaxSetsRestApiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductTaxSetsRestApi\Persistence\ProductTaxSetsRestApiEntityManagerInterface getEntityManager()
 */
class ProductTaxSetsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductTaxSetsRestApi\Business\ProductTaxSet\ProductTaxSetWriterInterface
     */
    public function createTaxSetWriter(): ProductTaxSetWriterInterface
    {
        return new ProductTaxSetWriter($this->getEntityManager());
    }
}
