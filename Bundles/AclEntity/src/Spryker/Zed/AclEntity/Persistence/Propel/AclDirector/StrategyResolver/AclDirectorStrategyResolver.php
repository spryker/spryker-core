<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface;

class AclDirectorStrategyResolver implements AclDirectorStrategyResolverInterface
{
    /**
     * @var array<\Closure>
     */
    protected $strategyContainer;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface
     */
    protected $aclEntityRuleCollectionTransferSorter;

    /**
     * @param array<\Closure> $strategyContainer
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     * @param \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
     */
    public function __construct(
        array $strategyContainer,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter,
        AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
    ) {
        $this->strategyContainer = $strategyContainer;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
        $this->aclEntityRuleCollectionTransferSorter = $aclEntityRuleCollectionTransferSorter;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function resolveByQuery(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclQueryDirectorStrategyInterface {
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
            $aclEntityRuleScope = $aclEntityRuleTransfer->getScopeOrFail();
            if (in_array($aclEntityRuleScope, [AclEntityConstants::SCOPE_GLOBAL, AclEntityConstants::SCOPE_SEGMENT])) {
                return call_user_func(
                    $this->strategyContainer[$aclEntityRuleScope],
                    $this->aclEntityRuleCollectionTransferFilter->filterByScopeEntityClassAndPermissionMask(
                        $aclEntityRuleCollectionTransfer,
                        $aclEntityRuleScope,
                        $query->getModelName(),
                        $operationMask,
                    ),
                );
            }

            if ($aclEntityRuleScope === AclEntityConstants::SCOPE_INHERITED) {
                return call_user_func(
                    $this->strategyContainer[AclEntityConstants::SCOPE_INHERITED],
                    $aclEntityRuleCollectionTransfer,
                );
            }
        }

        return call_user_func($this->strategyContainer[AclEntityConstants::SCOPE_DEFAULT]);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function resolveByEntity(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclQueryDirectorStrategyInterface {
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

            if ($aclEntityRuleTransfer->getScopeOrFail() === AclEntityConstants::SCOPE_GLOBAL) {
                return call_user_func(
                    $this->strategyContainer[AclEntityConstants::SCOPE_GLOBAL],
                    $this->aclEntityRuleCollectionTransferFilter->filterByScopeEntityClassAndPermissionMask(
                        $aclEntityRuleCollectionTransfer,
                        AclEntityConstants::SCOPE_GLOBAL,
                        get_class($entity),
                        $operationMask,
                    ),
                );
            }

            if ($aclEntityRuleTransfer->getScope() === AclEntityConstants::SCOPE_SEGMENT) {
                return call_user_func(
                    $this->strategyContainer[AclEntityConstants::SCOPE_SEGMENT],
                    $this->aclEntityRuleCollectionTransferFilter->filterByScopeEntityClassAndPermissionMask(
                        $aclEntityRuleCollectionTransfer,
                        AclEntityConstants::SCOPE_SEGMENT,
                        get_class($entity),
                        $operationMask,
                    ),
                );
            }

            if ($aclEntityRuleTransfer->getScopeOrFail() === AclEntityConstants::SCOPE_INHERITED) {
                return call_user_func(
                    $this->strategyContainer[AclEntityConstants::SCOPE_INHERITED],
                    $aclEntityRuleCollectionTransfer,
                );
            }
        }

        return call_user_func($this->strategyContainer[AclEntityConstants::SCOPE_DEFAULT]);
    }
}
