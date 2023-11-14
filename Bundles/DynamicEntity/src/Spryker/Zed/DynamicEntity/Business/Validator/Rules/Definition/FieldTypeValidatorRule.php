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

class FieldTypeValidatorRule implements ValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const FIELD_TYPE_BOOLEAN = 'boolean';

    /**
     * @var string
     */
    protected const FIELD_TYPE_DECIMAL = 'decimal';

    /**
     * @var string
     */
    protected const FIELD_TYPE_FLOAT = 'string';

    /**
     * @var string
     */
    protected const FIELD_TYPE_INTEGER = 'integer';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_TYPE = 'Field type is not allowed for dynamic entity. Type: %s';

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
        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer */
        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $filedTransfers = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions();

            foreach ($filedTransfers as $fieldTransfer) {
                $errorCollectionTransfer = $this->validateFieldType($fieldTransfer, $errorCollectionTransfer);
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @return array<string>
     */
    protected function getFieldTypes(): array
    {
        return [
            static::FIELD_TYPE_BOOLEAN,
            static::FIELD_TYPE_DECIMAL,
            static::FIELD_TYPE_FLOAT,
            static::FIELD_TYPE_INTEGER,
        ];
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
        if (!in_array($dynamicEntityFieldDefinitionTransfer->getType(), $this->getFieldTypes())) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE_INVALID_TYPE, $dynamicEntityFieldDefinitionTransfer->getType()))
                    ->setParameters([static::TYPE => static::FIELD_DEFINITIONS])
                    ->setEntityIdentifier($dynamicEntityFieldDefinitionTransfer->getType()),
            );
        }

        return $errorCollectionTransfer;
    }
}
