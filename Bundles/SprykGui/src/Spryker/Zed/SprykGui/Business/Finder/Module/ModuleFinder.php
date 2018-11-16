<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Module;

use Generated\Shared\Transfer\ModuleFilterTransfer;
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeInterface;

class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeInterface
     */
    protected $developmentFacade;

    /**
     * @param \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeInterface $developmentFacade
     */
    public function __construct(SprykGuiToDevelopmentFacadeInterface $developmentFacade)
    {
        $this->developmentFacade = $developmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function findModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        return $this->developmentFacade->getModules($moduleFilterTransfer);
    }
}
