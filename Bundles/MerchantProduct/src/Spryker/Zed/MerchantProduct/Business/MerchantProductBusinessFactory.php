<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductCartValidator;
use Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductCartValidatorInterface;
use Spryker\Zed\MerchantProduct\Business\Reader\ProductAbstractReader;
use Spryker\Zed\MerchantProduct\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\MerchantProduct\Business\Updater\ProductAbstractUpdater;
use Spryker\Zed\MerchantProduct\Business\Updater\ProductAbstractUpdaterInterface;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface;
use Spryker\Zed\MerchantProduct\MerchantProductDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 */
class MerchantProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductCartValidatorInterface
     */
    public function createMerchantProductCartValidator(): MerchantProductCartValidatorInterface
    {
        return new MerchantProductCartValidator(
            $this->getRepository()
        );
    }
    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Reader\ProductAbstractReaderInterface
     */
    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getRepository(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Updater\ProductAbstractUpdaterInterface
     */
    public function createProductAbstractUpdater(): ProductAbstractUpdaterInterface
    {
        return new ProductAbstractUpdater(
            $this->getRepository(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface
     */
    public function getProductFacade(): MerchantProductToProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDependencyProvider::FACADE_PRODUCT);
    }
}
