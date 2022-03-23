<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Propel\Runtime\ActiveQuery\PropelQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class TargetEntityReferenceToFkEntityStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const ENTITY_NOT_FOUND_MESSAGE_TEMPLATE = 'Failed to find %s entity by given %s.';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $entityReference = $dataSet[AclEntitySegmentConnectorDataSetInterface::ENTITY_REFERENCE];
        $referenceField = $dataSet[AclEntitySegmentConnectorDataSetInterface::REFERENCE_FIELD];
        $dataEntity = $dataSet[AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY];

        $targetEntity = PropelQuery::from($dataEntity);
        $referenceFieldColumnName = $targetEntity->getTableMapOrFail()->getColumn($referenceField)->getPhpNameOrFail();
        $targetEntity = $targetEntity
            ->filterBy($referenceFieldColumnName, $entityReference)
            ->findOne();

        if (!$targetEntity) {
            throw new EntityNotFoundException(
                sprintf(static::ENTITY_NOT_FOUND_MESSAGE_TEMPLATE, $dataEntity, $referenceField),
            );
        }

        $dataSet[AclEntitySegmentConnectorDataSetInterface::FK_TARGET_ENTITY] = $targetEntity
            ->getPrimaryKey();
    }
}
