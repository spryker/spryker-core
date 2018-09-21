<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Dependency\Facade;

class SprykGuiToDevelopmentFacadeBridge implements SprykGuiToDevelopmentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DevelopmentFacadeInterface
     */
    protected $developmentFacade;

    /**
     * @param \Spryker\Zed\Development\Business\DevelopmentFacadeInterface $developmentFacade
     */
    public function __construct($developmentFacade)
    {
        $this->developmentFacade = $developmentFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(): array
    {
        return $this->developmentFacade->getModules();
    }
}
