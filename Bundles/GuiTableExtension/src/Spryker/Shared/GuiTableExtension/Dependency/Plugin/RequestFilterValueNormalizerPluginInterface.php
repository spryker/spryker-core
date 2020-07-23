<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTableExtension\Dependency\Plugin;

/**
 * Provides capabilities to normalize data for filters.
 *
 * Use this plugin when a filter is type-sensitive or uses complex data structure.
 * Example: Parsing the HTML form 'date to' + 'date from' fields to a specific transfer.
 *
 * ONLY if it is a generic normalizer.
 *
 * @deprecated will be removed without replacement.
 */
interface RequestFilterValueNormalizerPluginInterface
{
    /**
     * Specification:
     * - Returns applicable filter type.
     *
     * @api
     *
     * @return string
     */
    public function getFilterType(): string;

    /**
     * Specification:
     * - Normalizes incoming value to the certain data structure.
     *
     * @api
     *
     * @param int|string|bool|int[]|string[] $value
     *
     * @return mixed
     */
    public function normalizeFilterValue($value);
}
