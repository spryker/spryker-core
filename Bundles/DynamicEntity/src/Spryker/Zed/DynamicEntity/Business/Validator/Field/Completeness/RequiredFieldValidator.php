<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;

class RequiredFieldValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const PLACEHOLDER_FILD_NAME = '%fieldName%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING = 'dynamic_entity.validation.required_field_is_missing';

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
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityTransfer,
                $dynamicEntityDefinitionTransfer,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param string $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidation(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        string $entityIdentifier
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getValidation() === null) {
                continue;
            }

            if ($fieldDefinitionTransfer->getValidation()->getIsRequired() === false) {
                continue;
            }

            if (isset($dynamicEntityTransfer->getFields()[$fieldDefinitionTransfer->getFieldVisibleName()])) {
                continue;
            }

            $dynamicEntityCollectionResponseTransfer->addError(
                $this->buildErrorTransfer($entityIdentifier, $fieldDefinitionTransfer->getFieldVisibleNameOrFail()),
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param string $entityIdentifier
     * @param string $fieldName
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function buildErrorTransfer(
        string $entityIdentifier,
        string $fieldName
    ): ErrorTransfer {
        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage(static::GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING)
            ->setParameters([
                static::PLACEHOLDER_FILD_NAME => $fieldName,
            ]);
    }
}
