<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine;

class EngineCollection implements EngineCollectionInterface
{

    /**
     * @var array
     */
    protected $engines = [];

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
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface $engine
     *
     * @return $this
     */
    public function addEngine(EngineInterface $engine)
    {
        $this->engines[$engine->getEngine()] = $engine;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface
     */
    public function getEngine()
    {
        return $this->engines[$this->currentEngine];
    }

    /**
     * @param string $engine
     *
     * @return bool
     */
    public function hasEngine($engine)
    {
        return isset($this->engines[$engine]);
    }

}
