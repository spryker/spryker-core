<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
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
        $options = [];

        if ($selectedSpryk) {
            $options['spryk'] = $selectedSpryk;
        }

        return $options;
    }

    /**
     * @param null|string $spryk
     * @param \Generated\Shared\Transfer\ModuleTransfer|null $moduleTransfer
     *
     * @return array
     */
    public function getData(?string $spryk = null, ?ModuleTransfer $moduleTransfer = null): array
    {
        if (!$moduleTransfer) {
            $moduleTransfer = new ModuleTransfer();
        }
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName('Spryker')
            ->setRootPath(APPLICATION_ROOT_DIR . 'vendor/spryker/spryker/');

        $moduleTransfer->setOrganization($organizationTransfer);

        return [
            'spryk' => $spryk,
            'module' => $moduleTransfer,
            'organization' => $organizationTransfer,
        ];
    }
}
