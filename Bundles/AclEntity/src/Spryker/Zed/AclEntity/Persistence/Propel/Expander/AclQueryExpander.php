<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Expander;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver\AclEntityConnectionResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class AclQueryExpander implements AclQueryExpanderInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver\AclEntityConnectionResolverInterface
     */
    protected $aclEntityConnectionResolver;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver\AclEntityConnectionResolverInterface $aclEntityConnectionResolver
     */
    public function __construct(
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        AclEntityConnectionResolverInterface $aclEntityConnectionResolver
    ) {
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->aclEntityConnectionResolver = $aclEntityConnectionResolver;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        string $joinType = Criteria::INNER_JOIN
    ): ModelCriteria {
        $aclEntityConnection = $this->aclEntityConnectionResolver->resolve($aclEntityMetadataTransfer);

        return $aclEntityConnection->joinRelation($query, $aclEntityMetadataTransfer, $joinType);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $parentAclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinEntityParent(
        ModelCriteria $query,
        AclEntityMetadataTransfer $parentAclEntityMetadataTransfer,
        string $joinType = Criteria::INNER_JOIN
    ): ModelCriteria {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
            $query->getModelNameOrFail(),
        );
        while ($aclEntityMetadataTransfer->getEntityNameOrFail() !== $parentAclEntityMetadataTransfer->getEntityNameOrFail()) {
            $query = $this->joinRelation($query, $aclEntityMetadataTransfer, $joinType);
            $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $query;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinSubEntityRootRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        string $joinType = Criteria::INNER_JOIN
    ): ModelCriteria {
        while ($aclEntityMetadataTransfer->getIsSubEntity()) {
            $query = $this->joinRelation($query, $aclEntityMetadataTransfer, $joinType);

            $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $query;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinEntityRootRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        string $joinType = Criteria::INNER_JOIN
    ): ModelCriteria {
        while ($aclEntityMetadataTransfer->getParent()) {
            $query = $this->joinRelation($query, $aclEntityMetadataTransfer, $joinType);
            $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $query;
    }
}
