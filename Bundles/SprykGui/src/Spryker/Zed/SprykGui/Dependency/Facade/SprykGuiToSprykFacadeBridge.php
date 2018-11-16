<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Dependency\Facade;

class SprykGuiToSprykFacadeBridge implements SprykGuiToSprykFacadeInterface
{
    /**
     * @var \Spryker\Spryk\SprykFacadeInterface
     */
    protected $sprykFacade;

    /**
     * @param \Spryker\Spryk\SprykFacadeInterface $sprykFacade
     */
    public function __construct($sprykFacade)
    {
        $this->sprykFacade = $sprykFacade;
    }

    /**
     * @return array
     */
    public function getSprykDefinitions(): array
    {
        return $this->sprykFacade->getSprykDefinitions();
    }
}
