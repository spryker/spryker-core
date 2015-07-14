<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\ProductExporter\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;

class ProductResourceCreator extends AbstractPlugin
{

    /**
     * @return ProductResourceCreator
     */
    public function createProductResourceCreator()
    {
        return $this->getDependencyContainer()->createProductResourceCreator();
    }

}
