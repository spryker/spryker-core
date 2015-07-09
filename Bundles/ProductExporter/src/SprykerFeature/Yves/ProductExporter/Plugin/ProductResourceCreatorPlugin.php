<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\ProductExporter\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\ProductExporter\ResourceCreator\ProductResourceCreator;

class ProductResourceCreatorPlugin extends AbstractPlugin
{

    /**
     * @return ProductResourceCreator
     */
    public function createProductResourceCreator()
    {
        return $this->getDependencyContainer()->createProductResourceCreator();
    }

}
