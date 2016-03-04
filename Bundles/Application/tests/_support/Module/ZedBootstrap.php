<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Application\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Spryker\Zed\Application\Communication\ZedBootstrap as SprykerZedBootstrap;

class ZedBootstrap extends Module
{

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

//        $application = new SprykerZedBootstrap();
//        $application->boot();
    }

}
