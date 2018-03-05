<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUserPluginExecutor implements CompanyUserPluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserPostSavePluginInterface[]
     */
    protected $companyUserPostSavePlugins;

    /**
     * @var array|\Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserHydrationPluginInterface[]
     */
    protected $companyUserHydrationPlugins;

    /**
     * @param \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserPostSavePluginInterface[] $companyUserPostSavePlugins
     * @param \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserHydrationPluginInterface[] $companyUserHydrationPlugins
     */
    public function __construct(
        array $companyUserPostSavePlugins = [],
        array $companyUserHydrationPlugins = []
    ) {
        $this->companyUserPostSavePlugins = $companyUserPostSavePlugins;
        $this->companyUserHydrationPlugins = $companyUserHydrationPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function executePostSavePlugins(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        foreach ($this->companyUserPostSavePlugins as $companyUserPostSavePlugin) {
            $companyUserTransfer = $companyUserPostSavePlugin->postSave($companyUserTransfer);
        }

        return $companyUserTransfer;
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
}
