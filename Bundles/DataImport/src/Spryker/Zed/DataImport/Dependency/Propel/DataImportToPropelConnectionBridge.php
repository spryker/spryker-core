<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Propel;

use Propel\Runtime\Connection\ConnectionInterface;

class DataImportToPropelConnectionBridge implements DataImportToPropelConnectionInterface
{

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $propelConnection;

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $propelConnection
     */
    public function __construct(ConnectionInterface $propelConnection)
    {
        $this->propelConnection = $propelConnection;
    }

    /**
     * @return bool
     */
    public function inTransaction()
    {
        return $this->propelConnection->inTransaction();
    }

    /**
     * @return void
     */
    public function beginTransaction()
    {
        $this->propelConnection->beginTransaction();
    }

    /**
     * @return void
     */
    public function endTransaction()
    {
        $this->propelConnection->commit();
    }

}
