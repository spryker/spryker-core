<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTableExtension\Dependency\Plugin;

/**
 * Provides capabilities to normalize column data returned as response.
 *
 * Use this plugin if you want to define a cross-module re-usable column type formatting.
 * Example: any table with "date" column type needs to render the value according ISO8601.
 *
 * ONLY if it is a generic formatter.
 *
 * @deprecated will be removed without replacement.
 */
interface ResponseColumnValueFormatterPluginInterface
{
    /**
     * Specification:
     * - Returns applicable column type.
     *
     * @api
     *
     * @return string
     */
    public function getColumnType(): string;

    /**
     * Specification:
     * - Formats incoming value to another format.
     *
     * @api
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function formatColumnValue($value);
}
