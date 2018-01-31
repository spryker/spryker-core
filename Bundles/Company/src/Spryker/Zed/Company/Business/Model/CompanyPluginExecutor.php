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
     * @var \Spryker\Zed\Company\Dependency\Plugin\CompanyPreSavePluginInterface[]
     */
    protected $companyPreSavePlugins;

    /**
     * @param \Spryker\Zed\Company\Dependency\Plugin\CompanyPreSavePluginInterface[] $companyPreSavePlugins
     */
    public function __construct(
        array $companyPreSavePlugins = []
    ) {
        $this->companyPreSavePlugins = $companyPreSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function executeCompanyPreSavePlugins(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        foreach ($this->companyPreSavePlugins as $companyPreSavePlugin) {
            $companyTransfer = $companyPreSavePlugin->preSave($companyTransfer);
        }

        return $companyTransfer;
    }
}
