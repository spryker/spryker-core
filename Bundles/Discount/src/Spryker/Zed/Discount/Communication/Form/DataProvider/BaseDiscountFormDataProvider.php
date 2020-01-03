<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

class BaseDiscountFormDataProvider
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormDataProviderExpanderPluginInterface[] $formExpanderPlugins
     *
     * @return void
     */
    public function applyFormDataExpanderPlugins(array $formExpanderPlugins)
    {
        foreach ($formExpanderPlugins as $calculatorFormExpanderPlugin) {
            $this->data = $calculatorFormExpanderPlugin->expandDataProviderData($this->data);
            $this->options = $calculatorFormExpanderPlugin->expandDataProviderOptions($this->options);
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
