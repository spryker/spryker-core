<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesOms\Communication\FilePathResolver\FilePathResolver;
use Spryker\Zed\SalesOms\Communication\FilePathResolver\FilePathResolverInterface;
use Spryker\Zed\SalesOms\Communication\HeaderValidator\HeaderValidator;
use Spryker\Zed\SalesOms\Communication\HeaderValidator\HeaderValidatorInterface;
use Spryker\Zed\SalesOms\Dependency\Facade\SalesOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOms\Dependency\Service\SalesOmsToUtilDataReaderServiceInterface;
use Spryker\Zed\SalesOms\SalesOmsDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesOms\SalesOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesOms\Business\SalesOmsFacadeInterface getFacade()
 */
class SalesOmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesOms\Communication\HeaderValidator\HeaderValidatorInterface
     */
    public function createHeaderValidator(): HeaderValidatorInterface
    {
        return new HeaderValidator();
    }

    /**
     * @return \Spryker\Zed\SalesOms\Communication\FilePathResolver\FilePathResolverInterface
     */
    public function createFilePathResolver(): FilePathResolverInterface
    {
        return new FilePathResolver();
    }

    /**
     * @return \Spryker\Zed\SalesOms\Dependency\Facade\SalesOmsToOmsFacadeInterface
     */
    public function getOmsFacade(): SalesOmsToOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesOmsDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesOms\Dependency\Service\SalesOmsToUtilDataReaderServiceInterface
     */
    public function getUtilDataReaderService(): SalesOmsToUtilDataReaderServiceInterface
    {
        return $this->getProvidedDependency(SalesOmsDependencyProvider::SERVICE_UTIL_DATA_READER);
    }
}
