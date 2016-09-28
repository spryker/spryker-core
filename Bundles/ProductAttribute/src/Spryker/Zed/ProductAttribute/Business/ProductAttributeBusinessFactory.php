<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\Model\Reader;
use Spryker\Zed\ProductAttribute\Business\Model\Writer;
use Spryker\Zed\ProductAttribute\Business\Transfer\ProductAttributeTransferMapper;
use Spryker\Zed\ProductAttribute\ProductAttributeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 */
class ProductAttributeBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\ReaderInterface
     */
    public function createProductAttributeReader()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->createTransferMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\WriterInterface
     */
    public function createProductAttributeWriter()
    {
        return new Writer(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Transfer\ProductAttributeTransferMapper
     */
    public function createTransferMapper()
    {
        return new ProductAttributeTransferMapper(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::FACADE_LOCALE);
    }

}
