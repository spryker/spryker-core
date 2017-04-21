<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductGroup;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductGroup\Twig\ProductGroupTwigExtension;

/**
 * @method \Spryker\Client\ProductGroup\ProductGroupClientInterface getClient()
 */
class ProductGroupFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\ProductGroup\Twig\ProductGroupTwigExtension
     */
    public function createProductGroupTwigExtension()
    {
        return new ProductGroupTwigExtension($this->getClient(), $this->createApplication());
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function createApplication()
    {
        return $this->getProvidedDependency(ProductGroupDependencyProvider::PLUGIN_APPLICATION);
    }

}
