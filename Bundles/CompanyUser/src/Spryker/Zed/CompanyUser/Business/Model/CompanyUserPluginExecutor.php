<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUserPluginExecutor implements CompanyUserPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreSavePluginInterface[]
     */
    protected $companyUserPreSavePlugins;

    /**
     * @var \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostSavePluginInterface[]
     */
    protected $companyUserPostSavePlugins;

    /**
     * @var \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserHydrationPluginInterface[]
     */
    protected $companyUserHydrationPlugins;

    /**
     * @var \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface[]
     */
    protected $companyUserPostCreatePlugins;

    /**
     * @var \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreDeletePluginInterface[]
     */
    protected $companyUserPreDeletePlugins;

    /**
     * @param \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreSavePluginInterface[] $companyUserPreSavePlugins
     * @param \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostSavePluginInterface[] $companyUserPostSavePlugins
     * @param \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface[] $companyUserPostCreatePlugins
     * @param \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserHydrationPluginInterface[] $companyUserHydrationPlugins
     * @param \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreDeletePluginInterface[] $companyUserPreDeletePlugins
     */
    public function __construct(
        array $companyUserPreSavePlugins = [],
        array $companyUserPostSavePlugins = [],
        array $companyUserPostCreatePlugins = [],
        array $companyUserHydrationPlugins = [],
        array $companyUserPreDeletePlugins = []
    ) {
        $this->companyUserPostSavePlugins = $companyUserPostSavePlugins;
        $this->companyUserHydrationPlugins = $companyUserHydrationPlugins;
        $this->companyUserPostCreatePlugins = $companyUserPostCreatePlugins;
        $this->companyUserPreSavePlugins = $companyUserPreSavePlugins;
        $this->companyUserPreDeletePlugins = $companyUserPreDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function executePreSavePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer {
        foreach ($this->companyUserPreSavePlugins as $companyUserPreSavePlugin) {
            $companyUserResponseTransfer = $companyUserPreSavePlugin->preSave($companyUserResponseTransfer);
        }

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function executePostSavePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer {
        foreach ($this->companyUserPostSavePlugins as $companyUserPostSavePlugin) {
            $companyUserResponseTransfer = $companyUserPostSavePlugin->postSave($companyUserResponseTransfer);
        }

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function executeHydrationPlugins(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        foreach ($this->companyUserHydrationPlugins as $companyUserHydrationPlugin) {
            $companyUserTransfer = $companyUserHydrationPlugin->hydrate($companyUserTransfer);
        }

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function executePostCreatePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer {
        foreach ($this->companyUserPostCreatePlugins as $companyUserPostCreatePlugin) {
            $companyUserResponseTransfer = $companyUserPostCreatePlugin->postCreate($companyUserResponseTransfer);
        }

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function executePreDeletePlugins(CompanyUserTransfer $companyUserTransfer): void
    {
        foreach ($this->companyUserPreDeletePlugins as $plugin) {
            $plugin->preDelete($companyUserTransfer);
        }
    }
}
