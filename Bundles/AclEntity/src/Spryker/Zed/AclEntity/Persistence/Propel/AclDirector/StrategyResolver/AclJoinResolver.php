<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\AclEntity\Persistence\Exception\InvalidJoinTypeException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface;

class AclJoinResolver implements AclJoinResolverInterface
{
    /**
     * @var array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface>
     */
    protected $aclJoins;

    /**
     * @param array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface> $aclJoins
     */
    public function __construct(array $aclJoins)
    {
        $this->aclJoins = $aclJoins;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\InvalidJoinTypeException
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface
     */
    public function resolve(Join $join): AclJoinInterface
    {
        foreach ($this->aclJoins as $aclJoin) {
            if ($aclJoin->isSupported($join)) {
                return $aclJoin;
            }
        }

        throw new InvalidJoinTypeException($join->getJoinType());
    }
}
