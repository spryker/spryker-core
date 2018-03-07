<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\CompanyTransfer;

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
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function executeCompanyPreSavePlugins(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        foreach ($this->companyPreSavePlugins as $companyPreSavePlugin) {
            $companyTransfer = $companyPreSavePlugin->preSaveValidation($companyTransfer);
        }

        return $companyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function executeCompanyPostSavePlugins(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        foreach ($this->companyPostSavePlugins as $companyPostSavePlugin) {
            $companyTransfer = $companyPostSavePlugin->postSave($companyTransfer);
        }

        return $companyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function executeCompanyPostCreatePlugins(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        foreach ($this->companyPostCreatePlugins as $companyPostCreatePlugin) {
            $companyTransfer = $companyPostCreatePlugin->postCreate($companyTransfer);
        }

        return $companyTransfer;
    }
}
