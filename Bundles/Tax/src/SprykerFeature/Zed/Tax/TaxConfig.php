<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;

class TaxConfig extends AbstractBundleConfig
{

    /**
     * @return TaxChangePluginInterface[]
     */
    public function getTaxChangePlugins()
    {
        return [];
    }

}
