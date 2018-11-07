<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use Spryker\Zed\CategoryImage\Business\Model\Reader;
use Spryker\Zed\CategoryImage\Business\Model\ReaderInterface;
use Spryker\Zed\CategoryImage\Business\Model\Writer;
use Spryker\Zed\CategoryImage\Business\Model\WriterInterface;
use Spryker\Zed\CategoryImage\Business\Provider\LocaleProvider;
use Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface;
use Spryker\Zed\CategoryImage\CategoryImageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
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
            $this->createLocaleProvider()
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
            $this->createLocaleProvider()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface
     */
    public function createLocaleProvider(): LocaleProviderInterface
    {
        return new LocaleProvider(
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
}
