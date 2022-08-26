<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Symfony\Component\Routing\Route;

/**
 * Provides capability to extends route description table.
 */
interface TableColumnExpanderPluginInterface
{
    /**
     * Specification:
     * - Returns the header of the column.
     *
     * @api
     *
     * @return string
     */
    public function getHeader(): string;

    /**
     * Specification:
     * - Returns the row data.
     * - Runs on each row.
     *
     * @api
     *
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return string
     */
    public function getRowData(Route $route): string;

    /**
     * Specification:
     * - Returns a Glue Application name that the expander is applicable for.
     *
     * @api
     *
     * @return string
     */
    public function getApiApplicationName(): string;
}
