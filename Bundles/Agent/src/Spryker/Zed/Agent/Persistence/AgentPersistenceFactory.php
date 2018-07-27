<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Persistence;

use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class AgentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function createUserQuery()
    {
        return SpyUserQuery::create();
    }
}
