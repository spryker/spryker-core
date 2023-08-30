<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;

class FieldTypeDecimalValidatorRule extends AbstractFildTypeValidatorRule implements ValidatorRuleInterface, FildTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const FIELD_TYPE_DECIMAL = 'decimal';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRECISION_SCALE = 'Validation setting `scale` must be less than `precision` for `decimal` field type.';

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $dynamicEntityConfigurationTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer */
        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $dynamicEntityFieldDefinitionTransfers = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions();
            $errorCollectionTransfer = $this->validateFieldDefinitions($dynamicEntityFieldDefinitionTransfers, $errorCollectionTransfer);

            foreach ($dynamicEntityFieldDefinitionTransfers as $dynamicEntityFieldDefinitionTransfer) {
                if ($dynamicEntityFieldDefinitionTransfer->getType() !== $this->getFieldType()) {
                    continue;
                }

                $errorCollectionTransfer = $this->validateFieldType($dynamicEntityFieldDefinitionTransfer, $errorCollectionTransfer);
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateFieldType(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        $dynamicEntityFieldValidationTransfer = $dynamicEntityFieldDefinitionTransfer->getValidationOrFail();

        if (
            $dynamicEntityFieldValidationTransfer->getScale() !== null &&
            $dynamicEntityFieldValidationTransfer->getPrecision() !== null &&
            $dynamicEntityFieldValidationTransfer->getScale() >= $dynamicEntityFieldValidationTransfer->getPrecision()
        ) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE_PRECISION_SCALE))
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($dynamicEntityFieldDefinitionTransfer->getFieldName()),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @return array<string>
     */
    public function getAllowedValidationFields(): array
    {
        return [
            'isRequired',
            'precision',
            'scale',
        ];
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return static::FIELD_TYPE_DECIMAL;
    }
}
