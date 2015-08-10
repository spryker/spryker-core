<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Plugin;

use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Propel\Business\PropelFacade;

/**
 * @method PropelFacade getFacade()
 */
class Connection extends AbstractPlugin
{

    /**
     * @return ConnectionInterface
     */
    public function get()
    {
        $connection = Propel::getConnection();

        return $connection;
    }
}
