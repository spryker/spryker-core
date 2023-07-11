<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Type;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

abstract class AbstractFieldTypeValidator
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE = 'dynamic_entity.validation.invalid_field_type';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_FIELD_VALUE = 'dynamic_entity.validation.invalid_field_value';

    /**
     * @var string
     */
    protected const PLACEHOLDER_FIELD_NAME = '%fieldName%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_VALIDATION_RULES = '%validationRules%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ROW_NUMBER = '%rowNumber%';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $rowNumber = 1;
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $this->processValidation(
                $dynamicEntityTransfer,
                $dynamicEntityDefinitionTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
                $rowNumber,
            );
            $rowNumber++;
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param mixed $fieldValue
     *
     * @return bool
     */
    abstract public function isValidType(mixed $fieldValue): bool;

    /**
     * @param mixed $fieldValue
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return bool
     */
    abstract public function isValidValue(mixed $fieldValue, DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): bool;

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param string $entityIdentifier
     * @param int $rowNumber
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidation(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        string $entityIdentifier,
        int $rowNumber
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            $fieldName = $fieldDefinitionTransfer->getFieldVisibleNameOrFail();

            if (!isset($dynamicEntityTransfer->getFields()[$fieldName]) || !$this->isSupportType($fieldDefinitionTransfer->getTypeOrFail())) {
                continue;
            }

            $fieldValue = $dynamicEntityTransfer->getFields()[$fieldName];

            if ($this->isValidType($fieldValue) === false) {
                $dynamicEntityCollectionResponseTransfer->addError($this->buildTypeErrorTransfer($entityIdentifier, $fieldName));
            }

            if ($this->isValidValue($fieldValue, $fieldDefinitionTransfer) === false) {
                $dynamicEntityCollectionResponseTransfer->addError(
                    $this->buildValueErrorTransfer(
                        $entityIdentifier,
                        $fieldDefinitionTransfer->getValidationOrFail()->toArray(),
                        $fieldName,
                        $rowNumber,
                    ),
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isSupportType(string $type): bool
    {
        return $type === $this->getType();
    }

    /**
     * @param string $entityIdentifier
     * @param string $fieldName
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function buildTypeErrorTransfer(
        string $entityIdentifier,
        string $fieldName
    ): ErrorTransfer {
        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage(static::GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE)
            ->setParameters([
                static::PLACEHOLDER_FIELD_NAME => $fieldName,
            ]);
    }

    /**
     * @param string $entityIdentifier
     * @param array<string, string> $validationRules
     * @param string $fieldName
     * @param int $rowNumber
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function buildValueErrorTransfer(
        string $entityIdentifier,
        array $validationRules,
        string $fieldName,
        int $rowNumber
    ): ErrorTransfer {
        $rules = [];
        foreach ($validationRules as $key => $value) {
            if ($value !== null) {
                $rules[] = sprintf('%s: %s', $key, $value);
            }
        }

        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage(static::GLOSSARY_KEY_ERROR_INVALID_FIELD_VALUE)
            ->setParameters([
                static::PLACEHOLDER_VALIDATION_RULES => implode(', ', $rules),
                static::PLACEHOLDER_FIELD_NAME => $fieldName,
                static::PLACEHOLDER_ROW_NUMBER => $rowNumber,
            ]);
    }
}
