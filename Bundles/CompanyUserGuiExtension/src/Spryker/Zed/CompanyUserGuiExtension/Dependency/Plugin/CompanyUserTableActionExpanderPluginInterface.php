<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;

interface CompanyUserTableActionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands action links in company user table in Zed.
     *
     * @api
     *
     * @param array $companyUserTableRowItem
     * @param string[] $buttons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function expand(array $companyUserTableRowItem, array $buttons): ButtonTransfer;
}
