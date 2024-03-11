<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\RawDynamicEntityTransfer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;

class DynamicEntityPostEditRequestExpander implements DynamicEntityPostEditRequestExpanderInterface
{
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
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityTransfer> $dynamicEntityTransfers
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer> $dynamicEntityPostEditRequestTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    public function expandDynamicEntityCollectionResponseTransferWithRawDynamicEntityTransfers(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ArrayObject $dynamicEntityTransfers,
        array $dynamicEntityPostEditRequestTransfers
    ): array {
        $tableName = $dynamicEntityConfigurationTransfer->getTableNameOrFail();
        $dynamicEntityPostEditRequestTransfers = $this->addPostEditRequestTransfer(
            $dynamicEntityPostEditRequestTransfers,
            $tableName,
        );

        $dynamicEntityPostEditRequestTransfer = $dynamicEntityPostEditRequestTransfers[$tableName];

        $indexedFieldNamesByVisibleName = $this->dynamicEntityIndexer->getFieldNamesIndexedByFieldVisibleName($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail());
        foreach ($dynamicEntityTransfers as $dynamicEntityTransfer) {
            $dynamicEntityFields = $this->dynamicEntityIndexer->getFieldValuesIndexedByFieldName($dynamicEntityTransfer, $indexedFieldNamesByVisibleName);
            $dynamicEntityPostEditRequestTransfer->addRawDynamicEntity(
                (new RawDynamicEntityTransfer())->setFields($dynamicEntityFields),
            );

            if ($dynamicEntityTransfer->getChildRelations()->count() === 0) {
                continue;
            }

            $dynamicEntityPostEditRequestTransfers = $this->expandDynamicEntityCollectionResponseTransferWithChildRawDynamicEntityTransfers(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer->getChildRelations(),
                $dynamicEntityPostEditRequestTransfers,
            );
        }

        return $dynamicEntityPostEditRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityRelationTransfer> $childRelationTransfers
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer> $dynamicEntityPostEditRequestTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    public function expandDynamicEntityCollectionResponseTransferWithChildRawDynamicEntityTransfers(
        DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer,
        ArrayObject $childRelationTransfers,
        array $dynamicEntityPostEditRequestTransfers
    ): array {
        foreach ($childRelationTransfers as $childRelationTransfer) {
            $dynamicEntityConfigurationTransfer = $this->findChildDynamicEntityConfigurationByRelationName(
                $parentDynamicEntityConfigurationTransfer,
                $childRelationTransfer->getNameOrFail(),
            );

            if ($dynamicEntityConfigurationTransfer === null) {
                continue;
            }

            $dynamicEntityPostEditRequestTransfers = $this->expandDynamicEntityCollectionResponseTransferWithRawDynamicEntityTransfers(
                $dynamicEntityConfigurationTransfer,
                $childRelationTransfer->getDynamicEntities(),
                $dynamicEntityPostEditRequestTransfers,
            );
        }

        return $dynamicEntityPostEditRequestTransfers;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer> $dynamicEntityPostEditRequestTransfers
     * @param string $tableName
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    protected function addPostEditRequestTransfer(array $dynamicEntityPostEditRequestTransfers, string $tableName): array
    {
        if (isset($dynamicEntityPostEditRequestTransfers[$tableName])) {
            return $dynamicEntityPostEditRequestTransfers;
        }

        $dynamicEntityPostEditRequestTransfers[$tableName] = (new DynamicEntityPostEditRequestTransfer())
            ->setTableName($tableName);

        return $dynamicEntityPostEditRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $relationName
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null
     */
    protected function findChildDynamicEntityConfigurationByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $relationName
    ): ?DynamicEntityConfigurationTransfer {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            if ($childRelation->getNameOrFail() === $relationName) {
                return $childRelation->getChildDynamicEntityConfigurationOrFail();
            }
        }

        return null;
    }
}
