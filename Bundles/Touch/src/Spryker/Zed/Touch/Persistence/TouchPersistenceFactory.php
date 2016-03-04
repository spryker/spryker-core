<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence;

use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Touch\TouchConfig getConfig()
 * @method \Spryker\Zed\Touch\Persistence\TouchQueryContainer getQueryContainer()
 */
class TouchPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function createTouchQuery()
    {
        return SpyTouchQuery::create();
    }

}
