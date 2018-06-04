<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReader;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeWriter;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeWriterInterface;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface getRepository()
 */
class ProductAlternativeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeWriterInterface
     */
    public function createProductAlternativeWriter(): ProductAlternativeWriterInterface
    {
        return new ProductAlternativeWriter(
            $this->getEntityManager(),
            $this->createProductAlternativeReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface
     */
    public function createProductAlternativeReader(): ProductAlternativeReaderInterface
    {
        return new ProductAlternativeReader(
            $this->getRepository()
        );
    }
}
