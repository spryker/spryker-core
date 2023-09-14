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

class RequestFieldValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const PLACEHOLDER_FILD_NAME = '%fieldName%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PROVIDED_FIELD_IS_INVALID = 'dynamic_entity.validation.provided_field_is_invalid';

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
        $indexedDefinitions = $this->indexDefinitions($dynamicEntityDefinitionTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityCollectionResponseTransfer = $this->validateFieldNames(
                $dynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
     */
    protected function indexDefinitions(DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): array
    {
        $indexedDefinitions = [];
        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $dynamicEntityDefinitionField) {
            $indexedDefinitions[$dynamicEntityDefinitionField->getFieldVisibleNameOrFail()] = $dynamicEntityDefinitionField;
        }

        return $indexedDefinitions;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $indexedDefinitions
     * @param string $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function validateFieldNames(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedDefinitions,
        string $entityIdentifier
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getFields() as $fieldName => $fieldValue) {
            if (isset($indexedDefinitions[$fieldName]) || $fieldName === static::IDENTIFIER) {
                continue;
            }

            $dynamicEntityCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($entityIdentifier)
                    ->setMessage(static::GLOSSARY_KEY_PROVIDED_FIELD_IS_INVALID)
                    ->setParameters([
                        static::PLACEHOLDER_FILD_NAME => $fieldName,
                    ]),
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }
}
