<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher\ProductAlternativePublisher;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher\ProductAlternativePublisherInterface;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher\ProductReplacementPublisher;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher\ProductReplacementPublisherInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface getRepository()
 */
class ProductAlternativeStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher\ProductAlternativePublisherInterface
     */
    public function createProductAlternativePublisher(): ProductAlternativePublisherInterface
    {
        return new ProductAlternativePublisher(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getProductAlternativeFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher\ProductReplacementPublisherInterface
     */
    public function createProductReplacementPublisher(): ProductReplacementPublisherInterface
    {
        return new ProductReplacementPublisher(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
