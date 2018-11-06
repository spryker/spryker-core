<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Dependency\Facade;

use Generated\Shared\Transfer\ModuleFilterTransfer;

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
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        return $this->developmentFacade->getModules($moduleFilterTransfer);
    }
}
