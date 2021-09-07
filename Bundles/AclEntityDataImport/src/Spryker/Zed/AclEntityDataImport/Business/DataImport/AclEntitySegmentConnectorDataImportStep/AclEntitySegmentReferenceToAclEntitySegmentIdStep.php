<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntitySegmentReferenceToAclEntitySegmentIdStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_NOT_FOUND_TEMPLATE = 'Failed to find AclEntitySegment by reference: "%s"';

    /**
     * @var int[]
     */
    protected $idAclEntitySegmentCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclEntitySegmentReference = $dataSet[AclEntitySegmentConnectorDataSetInterface::ACL_ENTITY_SEGMENT_REFERENCE];
        if (isset($this->idAclEntitySegmentCache[$aclEntitySegmentReference])) {
            $dataSet[AclEntitySegmentConnectorDataSetInterface::FK_ACL_ENTITY_SEGMENT]
                = $this->idAclEntitySegmentCache[$aclEntitySegmentReference];

            return;
        }
        $aclEntitySegmentEntity = SpyAclEntitySegmentQuery::create()
            ->filterByReference($aclEntitySegmentReference)
            ->findOne();
        if (!$aclEntitySegmentEntity) {
            throw new EntityNotFoundException(
                sprintf(static::ACL_ENTITY_SEGMENT_NOT_FOUND_TEMPLATE, $aclEntitySegmentReference)
            );
        }
        $this->idAclEntitySegmentCache[$aclEntitySegmentReference] = $aclEntitySegmentEntity->getIdAclEntitySegment();
        $dataSet[AclEntitySegmentConnectorDataSetInterface::FK_ACL_ENTITY_SEGMENT] = $aclEntitySegmentEntity
            ->getIdAclEntitySegment();
    }
}
