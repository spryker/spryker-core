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
     * @var \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserSavePluginInterface[]
     */
    protected $companyUserSavePlugins;

    /**
     * @var \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserHydrationPluginInterface[]
     */
    protected $companyUserHydrationPlugins;

    /**
     * @param \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserSavePluginInterface[] $companyUserSavePlugins
     * @param \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserHydrationPluginInterface[] $companyUserHydrationPlugins
     */
    public function __construct(
        array $companyUserSavePlugins = [],
        array $companyUserHydrationPlugins = []
    ) {
        $this->companyUserSavePlugins = $companyUserSavePlugins;
        $this->companyUserHydrationPlugins = $companyUserHydrationPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function executeCompanyUserSavePlugins(CompanyUserTransfer $companyUserTransfer): void
    {
        foreach ($this->companyUserSavePlugins as $companyUserSavePlugin) {
            $companyUserSavePlugin->save($companyUserTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function executeCompanyUserHydrationPlugins(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        foreach ($this->companyUserHydrationPlugins as $companyUserHydrationPlugin) {
            $companyUserTransfer = $companyUserHydrationPlugin->hydrate($companyUserTransfer);
        }

        return $companyUserTransfer;
    }
}
