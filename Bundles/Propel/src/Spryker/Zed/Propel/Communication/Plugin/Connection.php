<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Plugin;

use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacade getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
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
