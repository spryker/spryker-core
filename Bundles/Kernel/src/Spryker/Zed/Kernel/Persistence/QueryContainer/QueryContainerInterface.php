<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\QueryContainer;

interface QueryContainerInterface
{
    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function getConnection();
}
