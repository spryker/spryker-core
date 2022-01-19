<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\InvalidScopeException;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface;

class AclModelScopeResolver implements AclModelScopeResolverInterface
{
    /**
     * @var array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface>
     */
    protected $aclModelScopes;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface
     */
    protected $aclEntityRuleCollectionTransferSorter;

    /**
     * @param array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface> $aclModelScopes
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     * @param \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
     */
    public function __construct(
        array $aclModelScopes,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter,
        AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
    ) {
        $this->aclModelScopes = $aclModelScopes;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
        $this->aclEntityRuleCollectionTransferSorter = $aclEntityRuleCollectionTransferSorter;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    public function resolve(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclModelScopeInterface {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferSorter->sortByScopePriority(
            $aclEntityRuleCollectionTransfer,
        );

        foreach ($aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if (
                ($aclEntityRuleTransfer->getEntityOrFail() !== AclEntityConstants::WHILDCARD_ENTITY
                    && $aclEntityRuleTransfer->getEntityOrFail() !== get_class($entity)
                )
                || ($aclEntityRuleTransfer->getPermissionMaskOrFail() & $operationMask) === 0
            ) {
                continue;
            }

            return $this->getAclModelScopeByName($aclEntityRuleTransfer->getScopeOrFail());
        }

        return $this->getAclModelScopeByName(AclEntityConstants::SCOPE_DEFAULT);
    }

    /**
     * @param string $scope
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\InvalidScopeException
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    protected function getAclModelScopeByName(string $scope): AclModelScopeInterface
    {
        foreach ($this->aclModelScopes as $aclModelScope) {
            if ($aclModelScope->isSupported($scope)) {
                return $aclModelScope;
            }
        }

        throw new InvalidScopeException($scope);
    }
}
