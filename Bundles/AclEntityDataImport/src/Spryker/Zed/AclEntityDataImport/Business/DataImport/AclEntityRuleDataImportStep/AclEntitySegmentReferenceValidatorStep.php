<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep;

use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntityRuleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntitySegmentReferenceValidatorStep implements DataImportStepInterface
{
    /**
     * @var array
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
        $aclEntitySegmentReference = $dataSet[AclEntityRuleDataSetInterface::ACL_SEGMENT_REFERENCE];
        if (!$aclEntitySegmentReference || isset($this->aclEntitySegmentCache[$aclEntitySegmentReference])) {
            return;
        }

        $aclEntitySegmentQuery = SpyAclEntitySegmentQuery::create();
        $aclEntitySegmentCount = $aclEntitySegmentQuery
            ->filterByReference($aclEntitySegmentReference)
            ->count();

        if (!$aclEntitySegmentCount) {
            throw new EntityNotFoundException(
                sprintf('Could not find AclEntitySegment by reference: "%s"', $aclEntitySegmentReference)
            );
        }

        $this->aclEntitySegmentCache[$aclEntitySegmentReference] = true;
    }
}
