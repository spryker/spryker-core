<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\InvalidScopeException;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface;

class AclQueryScopeResolver implements AclQueryScopeResolverInterface
{
    /**
     * @var array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface>
     */
    protected $queryScopes;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface
     */
    protected $aclEntityRuleCollectionTransferSorter;

    /**
     * @param array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface> $queryScopes
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     * @param \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
     */
    public function __construct(
        array $queryScopes,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter,
        AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
    ) {
        $this->queryScopes = $queryScopes;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
        $this->aclEntityRuleCollectionTransferSorter = $aclEntityRuleCollectionTransferSorter;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    public function resolve(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclQueryScopeInterface {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferSorter->sortByScopePriority(
            $aclEntityRuleCollectionTransfer,
        );
        foreach ($aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if (
                !in_array($aclEntityRuleTransfer->getEntityOrFail(), [$query->getModelName(), AclEntityConstants::WHILDCARD_ENTITY], true)
                || ($aclEntityRuleTransfer->getPermissionMaskOrFail() & $operationMask) === 0
            ) {
                continue;
            }

            return $this->getQueryScope($aclEntityRuleTransfer->getScopeOrFail());
        }

        return $this->getQueryScope(AclEntityConstants::SCOPE_DEFAULT);
    }

    /**
     * @param string $scope
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\InvalidScopeException
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    protected function getQueryScope(string $scope): AclQueryScopeInterface
    {
        foreach ($this->queryScopes as $queryScope) {
            if ($queryScope->isSupported($scope)) {
                return $queryScope;
            }
        }

        throw new InvalidScopeException($scope);
    }
}
