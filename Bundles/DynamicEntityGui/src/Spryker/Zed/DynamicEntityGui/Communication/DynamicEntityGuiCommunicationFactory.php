<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication;

use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Propel\Runtime\Map\DatabaseMap;
use Propel\Runtime\Propel;
use Spryker\Zed\DynamicEntityGui\Communication\Form\CreateDynamicDataConfigurationForm;
use Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider\CreateDynamicDataConfigurationFormDataProvider;
use Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider\UpdateDynamicDataConfigurationFormDataProvider;
use Spryker\Zed\DynamicEntityGui\Communication\Form\UpdateDynamicDataConfigurationForm;
use Spryker\Zed\DynamicEntityGui\Communication\Mapper\DynamicDataConfigurationMapper;
use Spryker\Zed\DynamicEntityGui\Communication\Table\DynamicDataConfigurationTable;
use Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidator;
use Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface;
use Spryker\Zed\DynamicEntityGui\Dependency\External\DynamicEntityGuiToInflectorInterface;
use Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig getConfig()
 */
class DynamicEntityGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\DynamicEntityGui\Communication\Table\DynamicDataConfigurationTable
     */
    public function createDynamicDataConfigurationTable(): DynamicDataConfigurationTable
    {
        return new DynamicDataConfigurationTable(
            $this->getDynamicEntityPropelQuery(),
        );
    }

    /**
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    public function getDynamicEntityPropelQuery(): SpyDynamicEntityConfigurationQuery
    {
        return $this->getProvidedDependency(DynamicEntityGuiDependencyProvider::PROPEL_QUERY_DYNAMIC_ENTITY);
    }

    /**
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCreateDynamicDataConfigurationForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CreateDynamicDataConfigurationForm::class, null, $options);
    }

    /**
     * @param array<mixed> $dynamicDataConfiguration
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUpdateDynamicDataConfigurationForm(array $dynamicDataConfiguration, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(UpdateDynamicDataConfigurationForm::class, $dynamicDataConfiguration, $options);
    }

    /**
     * @return \Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider\CreateDynamicDataConfigurationFormDataProvider
     */
    public function createDynamicDataConfigurationFormDataProvider(): CreateDynamicDataConfigurationFormDataProvider
    {
        return new CreateDynamicDataConfigurationFormDataProvider(
            $this->getPropelDatabaseMap(),
            $this->createTableValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider\UpdateDynamicDataConfigurationFormDataProvider
     */
    public function createUpdateDynamicDataConfigurationFormDataProvider(): UpdateDynamicDataConfigurationFormDataProvider
    {
        return new UpdateDynamicDataConfigurationFormDataProvider(
            $this->getDynamicEntityFacade(),
            $this->getPropelDatabaseMap(),
            $this->createTableValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface
     */
    public function createTableValidator(): TableValidatorInterface
    {
        return new TableValidator(
            $this->getDynamicEntityFacade(),
            $this->getPropelDatabaseMap(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface
     */
    public function getDynamicEntityFacade(): DynamicEntityGuiToDynamicEntityFacadeInterface
    {
        return $this->getProvidedDependency(DynamicEntityGuiDependencyProvider::FACADE_DYNAMIC_ENTITY);
    }

    /**
     * @return \Propel\Runtime\Map\DatabaseMap
     */
    public function getPropelDatabaseMap(): DatabaseMap
    {
        return Propel::getDatabaseMap();
    }

    /**
     * @return \Spryker\Zed\DynamicEntityGui\Communication\Mapper\DynamicDataConfigurationMapper
     */
    public function createDynamicDataConfigurationMapper(): DynamicDataConfigurationMapper
    {
        return new DynamicDataConfigurationMapper(
            $this->getConfig(),
            $this->getInflector(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntityGui\Dependency\External\DynamicEntityGuiToInflectorInterface
     */
    public function getInflector(): DynamicEntityGuiToInflectorInterface
    {
        return $this->getProvidedDependency(DynamicEntityGuiDependencyProvider::INFLECTOR);
    }
}
