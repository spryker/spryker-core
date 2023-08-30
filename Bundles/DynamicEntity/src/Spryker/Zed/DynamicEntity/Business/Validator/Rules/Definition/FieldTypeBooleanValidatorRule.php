<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;

class FieldTypeBooleanValidatorRule extends AbstractFildTypeValidatorRule implements ValidatorRuleInterface, FildTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const FIELD_TYPE_BOOLEAN = 'boolean';

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
        ];
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return static::FIELD_TYPE_BOOLEAN;
    }
}
