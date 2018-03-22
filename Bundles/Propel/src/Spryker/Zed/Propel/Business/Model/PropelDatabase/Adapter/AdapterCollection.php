<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter;

class AdapterCollection implements AdapterCollectionInterface
{
    /**
     * @var array
     */
    protected $adapter = [];

    /**
     * @var string
     */
    protected $currentEngine;

    /**
     * @param string $currentEngine
     */
    public function __construct($currentEngine)
    {
        $this->currentEngine = $currentEngine;
    }

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterInterface $adapter
     *
     * @return $this
     */
    public function addAdapter(AdapterInterface $adapter)
    {
        $this->adapter[$adapter->getEngine()] = $adapter;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter[$this->currentEngine];
    }

    /**
     * @param string $adapter
     *
     * @return bool
     */
    public function hasAdapter($adapter)
    {
        return isset($this->adapter[$adapter]);
    }
}
