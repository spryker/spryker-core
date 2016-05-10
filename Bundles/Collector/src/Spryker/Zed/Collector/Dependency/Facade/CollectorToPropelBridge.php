<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Dependency\Facade;

class CollectorToPropelBridge implements CollectorToPropelInterface
{

    /**
     * @var \Spryker\Zed\Propel\Business\PropelFacade
     */
    protected $propelFacade;

    /**
     * @param \Spryker\Zed\Propel\Business\PropelFacade $propelFacade
     */
    public function __construct($propelFacade)
    {
        $this->propelFacade = $propelFacade;
    }

    /**
     * @return string
     */
    public function getCurrentDatabaseEngineName()
    {
        return $this->propelFacade->getCurrentDatabaseEngineName();
    }

}
