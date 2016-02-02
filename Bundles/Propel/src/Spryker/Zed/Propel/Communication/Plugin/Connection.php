<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Communication\Plugin;

use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Propel\Business\PropelFacade;
use Spryker\Zed\Propel\Communication\PropelCommunicationFactory;

/**
 * @method PropelFacade getFacade()
 * @method PropelCommunicationFactory getFactory()
 */
class Connection extends AbstractPlugin
{

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function get()
    {
        $connection = Propel::getConnection();

        return $connection;
    }

}
