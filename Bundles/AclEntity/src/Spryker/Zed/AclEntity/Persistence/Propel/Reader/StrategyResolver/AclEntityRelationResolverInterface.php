<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface;

interface AclEntityRelationResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface
     */
    public function resolve(AclEntityMetadataTransfer $aclEntityMetadataTransfer): AclEntityRelationInterface;
}
