<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Business;

use Spryker\Shared\ProductNew\ProductNewConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductNew\Business\Label\ProductAbstractRelationReader;

/**
 * @method \Spryker\Zed\ProductNew\ProductNewConfig getConfig()
 * @method \Spryker\Zed\ProductNew\Persistence\ProductNewQueryContainer getQueryContainer()
 */
class ProductNewBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductNew\Business\Label\ProductAbstractRelationReaderInterface
     */
    public function createProductAbstractRelationReader()
    {
        return new ProductAbstractRelationReader($this->getQueryContainer(), $this->createProductNewConfig());
    }

    /**
     * @return \Spryker\Shared\ProductNew\ProductNewConfig
     */
    protected function createProductNewConfig()
    {
        return new ProductNewConfig();
    }

}
