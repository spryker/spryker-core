<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantOms\Communication\FilePathResolver\FilePathResolver;
use Spryker\Zed\MerchantOms\Communication\FilePathResolver\FilePathResolverInterface;
use Spryker\Zed\MerchantOms\Communication\HeaderValidator\HeaderValidator;
use Spryker\Zed\MerchantOms\Communication\HeaderValidator\HeaderValidatorInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantOms\Dependency\Service\MerchantOmsToUtilDataReaderServiceInterface;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class MerchantOmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantOmsToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface[]
     */
    public function getStateMachineConditionPlugins(): array
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::PLUGINS_STATE_MACHINE_CONDITION);
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface[]
     */
    public function getStateMachineCommandPlugins(): array
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::PLUGINS_PLUGINS_STATE_MACHINE_COMMAND);
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Dependency\Service\MerchantOmsToUtilDataReaderServiceInterface
     */
    public function getUtilDataReaderService(): MerchantOmsToUtilDataReaderServiceInterface
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::SERVICE_UTIL_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Communication\HeaderValidator\HeaderValidatorInterface
     */
    public function createHeaderValidator(): HeaderValidatorInterface
    {
        return new HeaderValidator();
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Communication\FilePathResolver\FilePathResolverInterface
     */
    public function createFilePathResolver(): FilePathResolverInterface
    {
        return new FilePathResolver();
    }
}
