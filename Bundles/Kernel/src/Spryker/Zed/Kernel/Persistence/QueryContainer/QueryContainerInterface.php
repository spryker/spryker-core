<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence\QueryContainer;

use Propel\Runtime\Connection\ConnectionInterface;

interface QueryContainerInterface
{

    /**
     * @return ConnectionInterface
     */
    public function getConnection();

}
