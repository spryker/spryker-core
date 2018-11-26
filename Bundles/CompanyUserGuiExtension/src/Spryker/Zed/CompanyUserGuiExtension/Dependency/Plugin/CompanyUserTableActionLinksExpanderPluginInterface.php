<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin;

interface CompanyUserTableActionLinksExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands action links in company user table in Zed.
     *
     * @api
     *
     * @param array $companyUserDataItem
     * @param string[] $actionButtons
     *
     * @return string[]
     */
    public function expandActionLinks(array $companyUserDataItem, array $actionButtons): array;
}
