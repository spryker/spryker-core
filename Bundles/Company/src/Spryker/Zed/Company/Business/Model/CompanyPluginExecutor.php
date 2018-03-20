<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;

class CompanyPluginExecutor implements CompanyPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPreSavePluginInterface[]
     */
    protected $companyPreSavePlugins;

    /**
     * @var \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostSavePluginInterface[]
     */
    protected $companyPostSavePlugins;

    /**
     * @var \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface[]
     */
    protected $companyPostCreatePlugins;

    /**
     * @param \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPreSavePluginInterface[] $companyPreSavePlugins
     * @param \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostSavePluginInterface[] $companyPostSavePlugins
     * @param \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface[] $companyPostCreatePlugins
     */
    public function __construct(
        array $companyPreSavePlugins = [],
        array $companyPostSavePlugins = [],
        array $companyPostCreatePlugins = []
    ) {
        $this->companyPreSavePlugins = $companyPreSavePlugins;
        $this->companyPostCreatePlugins = $companyPostCreatePlugins;
        $this->companyPostSavePlugins = $companyPostSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function executeCompanyPreSavePlugins(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        foreach ($this->companyPreSavePlugins as $companyPreSavePlugin) {
            $companyResponseTransfer = $companyPreSavePlugin->preSaveValidation($companyResponseTransfer);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function executeCompanyPostSavePlugins(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        foreach ($this->companyPostSavePlugins as $companyPostSavePlugin) {
            $companyResponseTransfer = $companyPostSavePlugin->postSave($companyResponseTransfer);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function executeCompanyPostCreatePlugins(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        foreach ($this->companyPostCreatePlugins as $companyPostCreatePlugin) {
            $companyResponseTransfer = $companyPostCreatePlugin->postCreate($companyResponseTransfer);
        }

        return $companyResponseTransfer;
    }
}
