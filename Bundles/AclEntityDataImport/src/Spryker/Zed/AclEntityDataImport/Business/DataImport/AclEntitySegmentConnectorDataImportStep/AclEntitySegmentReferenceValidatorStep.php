<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntitySegmentReferenceValidatorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_NOT_FOUND_TEMPLATE = 'Failed to find %s by reference: "%s"';

    /**
     * @var bool[]
     */
    protected $aclEntitySegmentCache = [];

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
        if (isset($this->aclEntitySegmentCache[$aclEntitySegmentReference])) {
            return;
        }

        $aclEntitySegmentCount = SpyAclEntitySegmentQuery::create()
            ->filterByReference($aclEntitySegmentReference)
            ->count();

        if (!$aclEntitySegmentCount) {
            throw new EntityNotFoundException(
                sprintf(
                    static::ACL_ENTITY_SEGMENT_NOT_FOUND_TEMPLATE,
                    SpyAclEntitySegment::class,
                    $aclEntitySegmentReference
                )
            );
        }

        $this->aclEntitySegmentCache[$aclEntitySegmentReference] = true;
    }
}
