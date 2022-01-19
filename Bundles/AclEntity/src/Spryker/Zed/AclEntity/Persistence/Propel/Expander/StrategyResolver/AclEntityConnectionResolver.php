<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclEntity\Persistence\Exception\InvalidAclEntityMetadataConfigurationException;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface;

class AclEntityConnectionResolver implements AclEntityConnectionResolverInterface
{
    /**
     * @var array<\Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface>
     */
    protected $aclEntityConnections;

    /**
     * @param array<\Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface> $aclEntityConnections
     */
    public function __construct(array $aclEntityConnections)
    {
        $this->aclEntityConnections = $aclEntityConnections;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\InvalidAclEntityMetadataConfigurationException
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface
     */
    public function resolve(AclEntityMetadataTransfer $aclEntityMetadataTransfer): AclEntityConnectionInterface
    {
        foreach ($this->aclEntityConnections as $aclEntityConnection) {
            if ($aclEntityConnection->isSupported($aclEntityMetadataTransfer)) {
                return $aclEntityConnection;
            }
        }

        throw new InvalidAclEntityMetadataConfigurationException();
    }
}
