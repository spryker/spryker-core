<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxSetsRestApi\Business\TaxSet\TaxSetWriter;
use Spryker\Zed\TaxSetsRestApi\Business\TaxSet\TaxSetWriterInterface;

/**
 * @method \Spryker\Zed\TaxSetsRestApi\TaxSetsRestApiConfig getConfig()
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiEntityManagerInterface getEntityManager()
 */
class TaxSetsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\TaxSetsRestApi\Business\TaxSet\TaxSetWriterInterface
     */
    public function createTaxSetWriter(): TaxSetWriterInterface
    {
        return new TaxSetWriter($this->getEntityManager());
    }
}
