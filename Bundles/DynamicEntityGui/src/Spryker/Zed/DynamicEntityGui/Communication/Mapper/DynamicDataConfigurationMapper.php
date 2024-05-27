<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Mapper;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Zed\DynamicEntityGui\Communication\Form\UpdateDynamicDataConfigurationForm;
use Spryker\Zed\DynamicEntityGui\Dependency\External\DynamicEntityGuiToInflectorInterface;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig;

class DynamicDataConfigurationMapper
{
    /**
     * @var string
     */
    protected const IS_ENABLED = 'is_enabled';

    /**
     * @var \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig
     */
    protected DynamicEntityGuiConfig $config;

    /**
     * @var \Spryker\Zed\DynamicEntityGui\Dependency\External\DynamicEntityGuiToInflectorInterface
     */
    protected DynamicEntityGuiToInflectorInterface $inflector;

    /**
     * @param \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig $config
     * @param \Spryker\Zed\DynamicEntityGui\Dependency\External\DynamicEntityGuiToInflectorInterface $inflector
     */
    public function __construct(
        DynamicEntityGuiConfig $config,
        DynamicEntityGuiToInflectorInterface $inflector
    ) {
        $this->config = $config;
        $this->inflector = $inflector;
    }

    /**
     * @param array<mixed> $dynamicDataConfiguration
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer
     */
    public function mapDynamicDataConfigurationDataToCollectionRequestTransfer(
        array $dynamicDataConfiguration
    ): DynamicEntityConfigurationCollectionRequestTransfer {
        $dynamicEntityConfigurationTransfer = (new DynamicEntityConfigurationTransfer())
            ->fromArray($dynamicDataConfiguration, true);

        $dynamicEntityDefinitionTransfer = (new DynamicEntityDefinitionTransfer())
            ->setIdentifier($dynamicDataConfiguration[UpdateDynamicDataConfigurationForm::IDENTIFIER])
            ->setIsDeletable($dynamicDataConfiguration[UpdateDynamicDataConfigurationForm::FIELD_IS_DELETABLE]);
        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        foreach ($dynamicDataConfiguration[UpdateDynamicDataConfigurationForm::FIELD_DEFINITIONS] as $fieldDefinition) {
            $fieldDefinitionTransfer = $this->mapFieldDefinitionDataToTransfer($fieldDefinition);

            if ($fieldDefinitionTransfer === null) {
                continue;
            }

            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->addFieldDefinition($fieldDefinitionTransfer);
        }

        return (new DynamicEntityConfigurationCollectionRequestTransfer())
            ->addDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
    }

    /**
     * @param string $tableName
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer
     */
    public function mapInitialConfigurationDataToCollectionRequestTransfer(string $tableName): DynamicEntityConfigurationCollectionRequestTransfer
    {
        return (new DynamicEntityConfigurationCollectionRequestTransfer())
            ->addDynamicEntityConfiguration((new DynamicEntityConfigurationTransfer())
                ->setTableName($tableName)
                ->setTableAlias($this->normalizeTableAlias($tableName))
                ->setIsActive(false)
                ->setDynamicEntityDefinition(new DynamicEntityDefinitionTransfer()));
    }

    /**
     * @param array<mixed> $fieldDefinition
     *
     * @return \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer|null
     */
    protected function mapFieldDefinitionDataToTransfer(array $fieldDefinition): ?DynamicEntityFieldDefinitionTransfer
    {
        if ($fieldDefinition[static::IS_ENABLED] !== true) {
            return null;
        }

        $dynamicEntityFieldValidationTransfer = (new DynamicEntityFieldValidationTransfer())
            ->fromArray($fieldDefinition, true);

        $fieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->fromArray($fieldDefinition, true)
            ->setValidation($dynamicEntityFieldValidationTransfer);

        return $fieldDefinitionTransfer;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function normalizeTableAlias(string $name): string
    {
        foreach ($this->config->getTablePrefixes() as $prefix) {
            if (strpos($name, $prefix) === 0) {
                return $this->inflector->pluralize(
                    $this->convertUnderscoresToDashes(substr($name, strlen($prefix))),
                );
            }
        }

        return $this->inflector->pluralize($this->convertUnderscoresToDashes($name));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function convertUnderscoresToDashes(string $string): string
    {
        return str_replace('_', '-', $string);
    }
}
