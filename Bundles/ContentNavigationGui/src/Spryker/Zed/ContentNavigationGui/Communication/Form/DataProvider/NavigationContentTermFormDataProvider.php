<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Form\DataProvider;

use Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToNavigationFacadeInterface;

class NavigationContentTermFormDataProvider
{
    protected const NAVIGATION_CHOICE_PATTERN = '%s - %s';
    /**
     * @var \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToNavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @param \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToNavigationFacadeInterface $navigationFacade
     */
    public function __construct(ContentNavigationGuiToNavigationFacadeInterface $navigationFacade)
    {
        $this->navigationFacade = $navigationFacade;
    }

    /**
     * @return string[]
     */
    public function getNavigationChoices(): array
    {
        $navigationTransfers = $this->navigationFacade->getAllNavigations();

        $navigationChoices = [];
        foreach ($navigationTransfers as $navigationTransfer) {
            $navigationKey = $navigationTransfer->getKey();
            $navigationName = $navigationTransfer->getName();

            $navigationChoices[$navigationTransfer->getKey()] = sprintf(
                static::NAVIGATION_CHOICE_PATTERN,
                $navigationName,
                $navigationKey
            );
        }

        return array_flip($navigationChoices);
    }
}
