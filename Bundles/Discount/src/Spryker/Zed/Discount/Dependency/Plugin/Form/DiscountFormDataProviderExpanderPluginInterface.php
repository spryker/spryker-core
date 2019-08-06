<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin\Form;

interface DiscountFormDataProviderExpanderPluginInterface
{
    /**
     * Specification:
     *
     * Expand data provider options, the options will be passed from concrete form provider that is (calculator, general, general...)
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandDataProviderOptions(array $options);

    /**
     * Specification:
     *
     * Expand data provider data, the options will be passed from concrete form provider that is (calculator, general, general...)
     *
     * @api
     *
     * @param array|null $data
     *
     * @return array|null
     */
    public function expandDataProviderData(?array $data);
}
