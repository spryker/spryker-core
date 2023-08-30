<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;

class RequiredFieldsValidatorRule implements ValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REQUIRED_IDENTIFIER = 'Identifier is required for dynamic entity configuration.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_IDENTIFIER_NOT_DEFINED = 'Identifier is not defined in field definitions.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REQUIRED_TYPE = 'Field definition `type` is required for dynamic entity configuration.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REQUIRED_FIELD_NAME = 'Field definition `fieldName` is required for dynamic entity configuration.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REQUIRED_FIELD_VISIBLE_NAME = 'Field definition `fieldVisibleName` is required for dynamic entity configuration.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REQUIRED_FIELD_IS_CREATABLE = 'Field definition `isCreatable` is required for dynamic entity configuration.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REQUIRED_FIELD_IS_EDITABLE = 'Field definition `isEditable` is required for dynamic entity configuration.';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const FIELD_DEFINITIONS = 'field_definitions';

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $dynamicEntityConfigurationTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $errorCollectionTransfer = $this->validatedRequireFields($dynamicEntityConfigurationTransfer, $errorCollectionTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validatedRequireFields(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();

        if ($this->hasIdDynamicEntityConfiguration($dynamicEntityConfigurationTransfer) && $dynamicEntityDefinitionTransfer->getIdentifier() === null) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_REQUIRED_IDENTIFIER)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableName()),
            );
        }

        if ($dynamicEntityDefinitionTransfer->getIdentifier() !== null && !$this->isIdentifierPresentInDefinition($dynamicEntityDefinitionTransfer)) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_IDENTIFIER_NOT_DEFINED)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableName()),
            );
        }

        $dynamicEntityFieldDefinitionTransfers = $dynamicEntityDefinitionTransfer->getFieldDefinitions();

        $rowNumber = 1;
        foreach ($dynamicEntityFieldDefinitionTransfers as $dynamicEntityFieldDefinitionTransfer) {
            $entityIdentifier = sprintf('%s.row:%d', $dynamicEntityConfigurationTransfer->getTableName(), $rowNumber);
            $errorCollectionTransfer = $this->validateTypeField($dynamicEntityFieldDefinitionTransfer, $entityIdentifier, $errorCollectionTransfer);
            $errorCollectionTransfer = $this->validateFieldNameField($dynamicEntityFieldDefinitionTransfer, $entityIdentifier, $errorCollectionTransfer);
            $errorCollectionTransfer = $this->validateFieldVisibleNameField($dynamicEntityFieldDefinitionTransfer, $entityIdentifier, $errorCollectionTransfer);
            $errorCollectionTransfer = $this->validateRequireIsCreatableField($dynamicEntityFieldDefinitionTransfer, $entityIdentifier, $errorCollectionTransfer);
            $errorCollectionTransfer = $this->validateRequireIsEditableField($dynamicEntityFieldDefinitionTransfer, $entityIdentifier, $errorCollectionTransfer);
            $rowNumber++;
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateTypeField(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        string $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        if ($dynamicEntityFieldDefinitionTransfer->getType() === null) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_REQUIRED_TYPE)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($entityIdentifier),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateFieldNameField(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        string $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        if ($dynamicEntityFieldDefinitionTransfer->getFieldName() === null) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_REQUIRED_FIELD_NAME)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($entityIdentifier),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateFieldVisibleNameField(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        string $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        if ($dynamicEntityFieldDefinitionTransfer->getFieldVisibleName() === null) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_REQUIRED_FIELD_VISIBLE_NAME)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($entityIdentifier),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateRequireIsCreatableField(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        string $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        if ($dynamicEntityFieldDefinitionTransfer->getIsCreatable() === null) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_REQUIRED_FIELD_IS_CREATABLE)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($entityIdentifier),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateRequireIsEditableField(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        string $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        if ($dynamicEntityFieldDefinitionTransfer->getIsEditable() === null) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_REQUIRED_FIELD_IS_EDITABLE)
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($entityIdentifier),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return bool
     */
    protected function hasIdDynamicEntityConfiguration(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): bool
    {
        return $dynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return bool
     */
    protected function isIdentifierPresentInDefinition(DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): bool
    {
        $dynamicEntityFieldDefinitionTransfers = $dynamicEntityDefinitionTransfer->getFieldDefinitions();

        foreach ($dynamicEntityFieldDefinitionTransfers as $dynamicEntityFieldDefinitionTransfer) {
            if ($dynamicEntityFieldDefinitionTransfer->getFieldName() === $dynamicEntityDefinitionTransfer->getIdentifier()) {
                return true;
            }
        }

        return false;
    }
}
