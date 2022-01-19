<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface;

interface AclJoinResolverInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface
     */
    public function resolve(Join $join): AclJoinInterface;
}
