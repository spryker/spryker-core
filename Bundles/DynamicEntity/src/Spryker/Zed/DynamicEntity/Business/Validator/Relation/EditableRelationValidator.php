<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Relation;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;

class EditableRelationValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_NOT_EDITABLE_RELATION = 'dynamic_entity.validation.relation_is_not_editable';

    /**
     * @var string
     */
    protected const PLACEHOLDER_RELATION_NAME = '%relationName%';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $indexedChildRelations = $this->getChildTableAliasesIndexedByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityCollectionResponseTransfer = $this->validateRelationChains(
                $dynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $indexedChildRelations,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function validateRelationChains(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedChildRelations
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelation) {
            foreach ($childRelation->getDynamicEntities() as $dynamicEntityTransfer) {
                if ($indexedChildRelations[$childRelation->getNameOrFail()]->getIsEditableOrFail() === false) {
                    return $dynamicEntityCollectionResponseTransfer->addError(
                        (new ErrorTransfer())
                            ->setMessage(static::GLOSSARY_KEY_NOT_EDITABLE_RELATION)
                            ->setParameters([
                                static::PLACEHOLDER_RELATION_NAME => $childRelation->getNameOrFail(),
                            ]),
                    );
                }

                if ($dynamicEntityTransfer->getChildRelations()->count() > 0) {
                    $dynamicEntityCollectionResponseTransfer = $this->validateRelationChains(
                        $dynamicEntityTransfer,
                        $dynamicEntityCollectionResponseTransfer,
                        $indexedChildRelations,
                    );
                }
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected function getChildTableAliasesIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();

            $indexedChildRelations[$childRelation->getNameOrFail()] = $childRelation;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->getChildTableAliasesIndexedByRelationName($childDynamicEntityConfigurationTransfer, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }
}
