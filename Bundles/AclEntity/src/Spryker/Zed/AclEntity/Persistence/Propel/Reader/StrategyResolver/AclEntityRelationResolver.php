<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclEntity\Persistence\Exception\InvalidAclEntityMetadataConfigurationException;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface;

class AclEntityRelationResolver implements AclEntityRelationResolverInterface
{
    /**
     * @var array<\Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface>
     */
    protected $aclEntityRelations;

    /**
     * @param array<\Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface> $aclEntityRelations
     */
    public function __construct(array $aclEntityRelations)
    {
        $this->aclEntityRelations = $aclEntityRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\InvalidAclEntityMetadataConfigurationException
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface
     */
    public function resolve(AclEntityMetadataTransfer $aclEntityMetadataTransfer): AclEntityRelationInterface
    {
        foreach ($this->aclEntityRelations as $aclEntityRelation) {
            if ($aclEntityRelation->isSupported($aclEntityMetadataTransfer)) {
                return $aclEntityRelation;
            }
        }

        throw new InvalidAclEntityMetadataConfigurationException();
    }
}
