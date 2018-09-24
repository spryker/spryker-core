<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui;

interface CompanyUserTablePrepareDataExpanderPluginInterface
{
    /**
     * Specification:
     * - This plugin interface allows you to extend existing data rows of company user table in Zed.
     *
     * @api
     *
     * @param array $companyUserDataItem
     *
     * @return array
     */
    public function execute(array $companyUserDataItem): array;
}
