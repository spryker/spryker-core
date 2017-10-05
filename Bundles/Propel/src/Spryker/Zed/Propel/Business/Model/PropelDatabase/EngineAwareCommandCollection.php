<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

class EngineAwareCommandCollection implements EngineAwareCommandCollectionInterface
{

    /**
     * @var array
     */
    protected $engineAwareInvoker = [];

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\EngineAwareCommandInterface $engineAwareInvoker
     *
     * @return $this
     */
    public function add(EngineAwareCommandInterface $engineAwareInvoker)
    {
        $this->engineAwareInvoker[$engineAwareInvoker->getEngine()] = $engineAwareInvoker;

        return $this;
    }

    /**
     * @param string $engine
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\EngineAwareCommandInterface
     */
    public function get($engine)
    {
        return $this->engineAwareInvoker[$engine];
    }

    /**
     * @param string $engine
     *
     * @return bool
     */
    public function has($engine)
    {
        return isset($this->engineAwareInvoker[$engine]);
    }

}
