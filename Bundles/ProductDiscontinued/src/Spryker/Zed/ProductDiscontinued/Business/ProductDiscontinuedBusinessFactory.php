<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedReader\ProductDiscontinuedReader;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedReader\ProductDiscontinuedReaderInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedWriter\ProductDiscontinuedWriter;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedWriter\ProductDiscontinuedWriterInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface getEntityManager()()
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface getRepository()()
 */
class ProductDiscontinuedBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedWriter\ProductDiscontinuedWriterInterface
     */
    public function createProductDiscontinuedWriter(): ProductDiscontinuedWriterInterface
    {
        return new ProductDiscontinuedWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedReader\ProductDiscontinuedReaderInterface
     */
    public function createProductDiscontinuedReader(): ProductDiscontinuedReaderInterface
    {
        return new ProductDiscontinuedReader($this->getRepository());
    }
}
