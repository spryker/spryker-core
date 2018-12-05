<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin;

interface CompanyUserTableDeleteActionPluginInterface
{
    /**
     * Specification:
     *  - Returns custom "delete company user" action path.
     *
     * @api
     *
     * @return string
     */
    public function getPath(): string;
}
