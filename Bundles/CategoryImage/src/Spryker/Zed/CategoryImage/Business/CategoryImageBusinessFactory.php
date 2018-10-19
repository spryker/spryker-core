<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use Spryker\Zed\CategoryImage\Business\Model\CategoryImageSetCombiner;
use Spryker\Zed\CategoryImage\Business\Model\CategoryImageSetCombinerInterface;
use Spryker\Zed\CategoryImage\Business\Model\Reader;
use Spryker\Zed\CategoryImage\Business\Model\ReaderInterface;
use Spryker\Zed\CategoryImage\Business\Model\Writer;
use Spryker\Zed\CategoryImage\Business\Model\WriterInterface;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapper;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface;
use Spryker\Zed\CategoryImage\CategoryImageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 */
class CategoryImageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryImage\Business\Model\ReaderInterface
     */
    public function createCategoryImageReader(): ReaderInterface
    {
        return new Reader(
            $this->getRepository(),
            $this->createTransferGenerator(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CategoryImageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface
     */
    public function createTransferGenerator(): CategoryImageTransferMapperInterface
    {
        return new CategoryImageTransferMapper(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\Model\WriterInterface
     */
    public function createCategoryImageWriter(): WriterInterface
    {
        return new Writer(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\Model\CategoryImageSetCombinerInterface
     */
    public function createCategoryImageSetCombiner(): CategoryImageSetCombinerInterface
    {
        return new CategoryImageSetCombiner(
            $this->getRepository(),
            $this->createTransferGenerator()
        );
    }
}
