<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface FacetSearchResultValueTransformerPluginInterface
{
    /**
     * Specification:
     * - Transforms a value for display.
     *
     * @api
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformForDisplay($value);

    /**
     * Specification:
     * - Transforms a value from display.
     *
     * @api
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformFromDisplay($value);
}
