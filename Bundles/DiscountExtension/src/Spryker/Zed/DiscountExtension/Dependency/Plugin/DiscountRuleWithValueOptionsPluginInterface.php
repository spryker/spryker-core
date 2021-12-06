<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for the list of key-value pairs of the available select options.
 */
interface DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * Specification:
     * - Returns a list of key-value pairs of the available select options.
     *
     * @api
     *
     * @return array<int|string, string>
     */
    public function getQueryStringValueOptions();
}
