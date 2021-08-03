<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DataEntityValidatorStep implements DataImportStepInterface
{
    protected const REFERENCED_ENTITY_CLASS_WAS_NOT_FOUND_TEMPLATE = 'Referenced entity class was not found: %s';
    protected const REFERENCED_SEGMENT_CONNECTOR_CLASS_WAS_NOT_FOUND_TEMPLATE = 'Referenced segment connector class was not found: %s';

    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(AclEntityServiceInterface $aclEntityService)
    {
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $entityClass = $dataSet[AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY];
        if (!class_exists($entityClass)) {
            throw new DataImportException(
                sprintf(static::REFERENCED_ENTITY_CLASS_WAS_NOT_FOUND_TEMPLATE, $entityClass)
            );
        }

        $segmentConnectorClass = $this->aclEntityService->generateSegmentConnectorClassName($entityClass);
        if (!class_exists($segmentConnectorClass)) {
            throw new DataImportException(
                sprintf(static::REFERENCED_SEGMENT_CONNECTOR_CLASS_WAS_NOT_FOUND_TEMPLATE, $segmentConnectorClass)
            );
        }
    }
}
