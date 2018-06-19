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
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeUnpublisher\ProductAlternativeUnpublisher;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeUnpublisher\ProductAlternativeUnpublisherInterface;
use Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageDependencyProvider;

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
            $this->getProductAlternativeFacade(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeUnpublisher\ProductAlternativeUnpublisherInterface
     */
    public function createProductAlternativeUnublisher(): ProductAlternativeUnpublisherInterface
    {
        return new ProductAlternativeUnpublisher(
            $this->getEntityManager(),
            $this->getRepository()
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

    /**
     * @return mixed
     */
    public function getProductAlternativeFacade()
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::FACADE_PRODUCT_ALTERNATIVE);
    }
}
