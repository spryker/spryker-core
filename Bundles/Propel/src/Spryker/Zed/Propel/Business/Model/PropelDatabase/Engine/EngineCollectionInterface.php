<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine;

interface EngineCollectionInterface
{

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface $engine
     *
     * @return $this
     */
    public function addEngine(EngineInterface $engine);

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface
     */
    public function getEngine();

    /**
     * @param string $engine
     *
     * @return bool
     */
    public function hasEngine($engine);

}
