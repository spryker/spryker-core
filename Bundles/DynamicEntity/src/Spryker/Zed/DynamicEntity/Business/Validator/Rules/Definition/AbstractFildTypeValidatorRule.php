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

abstract class AbstractFildTypeValidatorRule implements FildTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const FIELD_DEFINITIONS = 'field_definitions';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_TPL = 'Validation rule(s): `%s` is not allowed for field type `%s`.';

    /**
     * @return array<string>
     */
    abstract public function getAllowedValidationFields(): array;

    /**
     * @return string
     */
    abstract public function getFieldType(): string;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $dynamicEntityFieldDefinitionTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateFieldDefinitions(
        ArrayObject $dynamicEntityFieldDefinitionTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        /**
         * @var \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
         */
        foreach ($dynamicEntityFieldDefinitionTransfers as $dynamicEntityFieldDefinitionTransfer) {
            if ($dynamicEntityFieldDefinitionTransfer->getType() !== $this->getFieldType()) {
                continue;
            }

            $notAllowedValidationFields = $this->filterNotAllowedValidationFields($dynamicEntityFieldDefinitionTransfer);

            if (count($notAllowedValidationFields) > 0) {
                $errorCollectionTransfer->addError(
                    (new ErrorTransfer())
                        ->setMessage(
                            sprintf(
                                static::ERROR_MESSAGE_TPL,
                                implode(',', $notAllowedValidationFields),
                                $dynamicEntityFieldDefinitionTransfer->getType(),
                            ),
                        )
                    ->setEntityIdentifier($dynamicEntityFieldDefinitionTransfer->getFieldName()),
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return array<string>
     */
    protected function filterNotAllowedValidationFields(DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): array
    {
        $allowedValidationFields = $this->getAllowedValidationFields();
        $validationFields = $dynamicEntityFieldDefinitionTransfer->getValidationOrFail()->toArray(false, true);
        $notAllowedFields = [];

        foreach ($validationFields as $key => $value) {
            if (!in_array($key, $allowedValidationFields) && $value !== null) {
                $notAllowedFields[] = $key;
            }
        }

        return $notAllowedFields;
    }
}
