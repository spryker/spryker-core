<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter\Validator;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

abstract class AbstractDynamicEntityPreValidator
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED = 'dynamic_entity.validation.modification_of_immutable_field_prohibited';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param array<mixed> $dynamicEntityFields
     * @param callable $filterCallback
     * @param string $identifier
     *
     * @return bool
     */
    abstract public function isFieldNonModifiable(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        array $dynamicEntityFields,
        callable $filterCallback,
        string $identifier
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param callable $filterCallback
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validate(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        callable $filterCallback,
        string $errorPath
    ): ?ErrorTransfer {
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();
        $identifier = $this->getIdentifierVisibleName(
            $dynamicEntityDefinitionTransfer->getIdentifierOrFail(),
            $dynamicEntityConfigurationTransfer,
        );

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($this->isFieldNonModifiable($fieldDefinitionTransfer, $dynamicEntityTransfer->getFields(), $filterCallback, $identifier) === true) {
                return (new ErrorTransfer())
                    ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableAliasOrFail())
                    ->setMessage(static::GLOSSARY_KEY_ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED)
                    ->setParameters([
                        DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldDefinitionTransfer->getFieldVisibleNameOrFail(),
                        DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]);
            }
        }

        return null;
    }

    /**
     * @param string $identifier
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getIdentifierVisibleName(string $identifier, DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getFieldNameOrFail() === $identifier) {
                return $fieldDefinitionTransfer->getFieldVisibleNameOrFail();
            }
        }

        return $identifier;
    }
}
