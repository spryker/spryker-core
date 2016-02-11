<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence\QueryContainer;

interface QueryContainerInterface
{

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function getConnection();

}
