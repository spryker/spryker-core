<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep;

use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntityRuleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclRoleReferenceValidatorStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $aclRoleReferenceCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclRoleReference = $dataSet[AclEntityRuleDataSetInterface::ACL_ROLE_REFERENCE];

        if (!isset($this->aclRoleReferenceCache[$aclRoleReference])) {
            $aclRoleQuery = SpyAclRoleQuery::create();
            $aclRoleCount = $aclRoleQuery
                ->filterByReference($aclRoleReference)
                ->count();

            if (!$aclRoleCount) {
                throw new EntityNotFoundException(sprintf('Could not find AclRole by reference: "%s"', $aclRoleReference));
            }

            $this->aclRoleReferenceCache[$aclRoleReference] = true;
        }
    }
}
