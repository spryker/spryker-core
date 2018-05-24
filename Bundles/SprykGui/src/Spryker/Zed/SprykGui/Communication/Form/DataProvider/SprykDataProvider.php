<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\DataProvider;

use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;

class SprykDataProvider
{
    /**
     * @var \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface
     */
    protected $sprykFacade;

    /**
     * @param \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface $sprykFacade
     */
    public function __construct(SprykGuiToSprykFacadeInterface $sprykFacade)
    {
        $this->sprykFacade = $sprykFacade;
    }

    /**
     * @param null|string $selectedSpryk
     *
     * @return array
     */
    public function getOptions(?string $selectedSpryk = null): array
    {
        $sprykDefinitions = $this->sprykFacade->getSprykDefinitions();

        $options = [
            'sprykDefinitions' => $sprykDefinitions,
        ];

        if ($selectedSpryk) {
            $options['spryk'] = $selectedSpryk;
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
