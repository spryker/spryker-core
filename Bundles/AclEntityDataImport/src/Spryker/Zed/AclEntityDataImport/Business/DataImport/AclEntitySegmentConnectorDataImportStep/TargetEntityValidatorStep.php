<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Propel\Runtime\ActiveQuery\PropelQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class TargetEntityValidatorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const ENTITY_NOT_FOUND_TEMPLATE = 'Failed to find %s by %s: "%s"';

    /**
     * @var string
     */
    protected const MULTIPLE_ENTITIES_FOUND_TEMPLATE = 'Multiple %s were found by %s: "%s"';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $query = PropelQuery::from($dataSet[AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY]);
        $columnName = $dataSet[AclEntitySegmentConnectorDataSetInterface::REFERENCE_FIELD];
        $entityCount = $query
            ->filterBy(
                $query->getTableMap()->getColumn($columnName)->getPhpName(),
                $dataSet[AclEntitySegmentConnectorDataSetInterface::ENTITY_REFERENCE]
            )
            ->count();

        if ($entityCount === 0) {
            throw new EntityNotFoundException(
                sprintf(
                    static::ENTITY_NOT_FOUND_TEMPLATE,
                    $dataSet[AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY],
                    $dataSet[AclEntitySegmentConnectorDataSetInterface::REFERENCE_FIELD],
                    $dataSet[AclEntitySegmentConnectorDataSetInterface::ENTITY_REFERENCE]
                )
            );
        }

        if ($entityCount > 1) {
            throw new DataImportException(
                sprintf(
                    static::MULTIPLE_ENTITIES_FOUND_TEMPLATE,
                    $dataSet[AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY],
                    $dataSet[AclEntitySegmentConnectorDataSetInterface::REFERENCE_FIELD],
                    $dataSet[AclEntitySegmentConnectorDataSetInterface::ENTITY_REFERENCE]
                )
            );
        }
    }
}
