<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MinimumOrderValue\Business\Applier\ThresholdApplier;
use Spryker\Zed\MinimumOrderValue\Business\Applier\ThresholdApplierInterface;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategy;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReader;
use Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdWriter;
use Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdWriterInterface;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstaller;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueType\MinimumOrderValueTypeReader;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueType\MinimumOrderValueTypeReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolver;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationReader;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriter;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueDependencyProvider;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface getRepository()
 * @method \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig getConfig()
 */
class MinimumOrderValueBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface
     */
    public function createMinimumOrderValueTypeInstaller(): MinimumOrderValueTypeInstallerInterface
    {
        return new MinimumOrderValueTypeInstaller(
            $this->getConfig()->getMinimumOrderValueStrategies(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueType\MinimumOrderValueTypeReaderInterface
     */
    public function createMinimumOrderValueTypeReader(): MinimumOrderValueTypeReaderInterface
    {
        return new MinimumOrderValueTypeReader(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface
     */
    public function createGlobalThresholdReader(): GlobalThresholdReaderInterface
    {
        return new GlobalThresholdReader(
            $this->getRepository(),
            $this->createMinimumOrderValueTranslationReader()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdWriterInterface
     */
    public function createGlobalThresholdWriter(): GlobalThresholdWriterInterface
    {
        return new GlobalThresholdWriter(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getEntityManager(),
            $this->createMinimumOrderValueTranslationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationReaderInterface
     */
    public function createMinimumOrderValueTranslationReader(): MinimumOrderValueTranslationReaderInterface
    {
        return new MinimumOrderValueTranslationReader(
            $this->getGlossaryFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface
     */
    public function createMinimumOrderValueTranslationWriter(): MinimumOrderValueTranslationWriterInterface
    {
        return new MinimumOrderValueTranslationWriter(
            $this->getGlossaryFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    public function createMinimumOrderValueStrategyResolver(): MinimumOrderValueStrategyResolverInterface
    {
        return new MinimumOrderValueStrategyResolver(
            $this->getConfig()->getMinimumOrderValueStrategies()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Applier\ThresholdApplierInterface
     */
    public function createThresholdApplier(): ThresholdApplierInterface
    {
        return new ThresholdApplier(
            $this->createThresholdDataSourceStrategy(),
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getConfig(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface
     */
    public function createThresholdDataSourceStrategy(): ThresholdDataSourceStrategyInterface
    {
        return new ThresholdDataSourceStrategy(
            $this->getMinimumOrderValueDataSourceStrategies(),
            $this->createGlobalThresholdReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    public function getMinimumOrderValueDataSourceStrategies(): array
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade(): MinimumOrderValueToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface
     */
    protected function getStoreFacade(): MinimumOrderValueToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface
     */
    protected function getMessengerFacade(): MinimumOrderValueToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_MESSENGER);
    }
}
