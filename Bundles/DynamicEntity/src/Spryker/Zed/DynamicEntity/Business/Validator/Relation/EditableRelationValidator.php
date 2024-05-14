<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Relation;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
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
     * @var \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface
     */
    protected DynamicEntityIndexerInterface $dynamicEntityIndexer;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface $dynamicEntityIndexer
     */
    public function __construct(DynamicEntityIndexerInterface $dynamicEntityIndexer)
    {
        $this->dynamicEntityIndexer = $dynamicEntityIndexer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param int $index
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        int $index
    ): array {
        $errorTransfers = [];
        $childRelationsIndexedByRelationName = $this->dynamicEntityIndexer->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelation) {
            $errorTransfers = array_merge($errorTransfers, $this->validateChildRelations(
                $dynamicEntityCollectionRequestTransfer,
                $childRelation,
                $dynamicEntityConfigurationTransfer,
                $childRelationsIndexedByRelationName,
                $index,
            ));
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityRelationTransfer $childRelation
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<mixed> $childRelationsIndexedByRelationName
     * @param int $index
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function validateChildRelations(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityRelationTransfer $childRelation,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $childRelationsIndexedByRelationName,
        int $index
    ): array {
        $errorTransfers = [];
        foreach ($childRelation->getDynamicEntities() as $dynamicEntityTransfer) {
            $editableRelationChildErrorTransfers = [];

            if ($childRelationsIndexedByRelationName[$childRelation->getNameOrFail()]->getIsEditableOrFail() === false) {
                $errorTransfers[] = (new ErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_NOT_EDITABLE_RELATION)
                    ->setParameters([
                        static::PLACEHOLDER_RELATION_NAME => $childRelation->getNameOrFail(),
                    ]);

                continue;
            }

            if ($dynamicEntityTransfer->getChildRelations()->count() > 0) {
                $editableRelationChildErrorTransfers = array_merge($editableRelationChildErrorTransfers, $this->validate(
                    $dynamicEntityCollectionRequestTransfer,
                    $dynamicEntityTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $index,
                ));
            }

            $errorTransfers = array_merge($errorTransfers, $editableRelationChildErrorTransfers);
        }

        return $errorTransfers;
    }
}
