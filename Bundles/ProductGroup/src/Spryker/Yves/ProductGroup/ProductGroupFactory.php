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
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createProductGroupTwigExtension()
    {
        return new ProductGroupTwigExtension($this->getClient(), $this->getLocale());
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        return $this->getProvidedDependency(ProductGroupDependencyProvider::SERVICE_LOCALE);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(ProductGroupDependencyProvider::PLUGIN_APPLICATION);
    }
}
