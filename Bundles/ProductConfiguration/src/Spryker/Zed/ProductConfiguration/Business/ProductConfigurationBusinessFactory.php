<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfiguration\Business\ProductConfiguration\Reader\ProductConfigurationReader;
use Spryker\Zed\ProductConfiguration\Business\ProductConfiguration\Reader\ProductConfigurationReaderInterface;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationEntityManagerInterface getEntityManager()
 */
class ProductConfigurationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\ProductConfiguration\Reader\ProductConfigurationReaderInterface
     */
    public function createProductConfigurationReader(): ProductConfigurationReaderInterface
    {
        return new ProductConfigurationReader(
            $this->getRepository(),
        );
    }
}
