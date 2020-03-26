<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantStock\Business\Writer\MerchantStockWriter;
use Spryker\Zed\MerchantStock\Business\Writer\MerchantStockWriterInterface;
use Spryker\Zed\MerchantStock\Dependency\Facade\MerchantStockToStockFacadeInterface;
use Spryker\Zed\MerchantStock\MerchantStockDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantStock\MerchantStockConfig getConfig()
 */
class MerchantStockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantStock\Business\Writer\MerchantStockWriterInterface
     */
    public function createMerchantStockWriter(): MerchantStockWriterInterface
    {
        return new MerchantStockWriter(
            $this->getStockFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantStock\Dependency\Facade\MerchantStockToStockFacadeInterface
     */
    public function getStockFacade(): MerchantStockToStockFacadeInterface
    {
        return $this->getProvidedDependency(MerchantStockDependencyProvider::FACADE_STOCK);
    }
}
