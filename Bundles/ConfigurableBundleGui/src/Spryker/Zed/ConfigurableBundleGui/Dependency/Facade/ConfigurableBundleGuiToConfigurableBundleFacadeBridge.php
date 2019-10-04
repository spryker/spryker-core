<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Dependency\Facade;

use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

class ConfigurableBundleGuiToConfigurableBundleFacadeBridge implements ConfigurableBundleGuiToConfigurableBundleFacadeInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface $configurableBundleFacade
     */
    public function __construct($configurableBundleFacade)
    {
        $this->configurableBundleFacade = $configurableBundleFacade;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deactivateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleFacade->deactivateConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function activateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleFacade->activateConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleFacade->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplate(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): ConfigurableBundleResponseTransfer
    {
        return $this->configurableBundleFacade->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplate(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): ConfigurableBundleResponseTransfer
    {
        return $this->configurableBundleFacade->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer {
        return $this->configurableBundleFacade->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlotCollection(ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer): array
    {
        return $this->configurableBundleFacade->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->configurableBundleFacade->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->configurableBundleFacade->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer {
        return $this->configurableBundleFacade->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return int
     */
    public function getProductListIdByIdConfigurableBundleTemplate(int $idConfigurableBundleTemplate): int
    {
        return $this->configurableBundleFacade->getProductListIdByIdConfigurableBundleTemplate($idConfigurableBundleTemplate);
    }
}
