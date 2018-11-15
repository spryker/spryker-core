<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication;

use Spryker\Zed\CategoryImageGui\CategoryImageGuiDependencyProvider;
use Spryker\Zed\CategoryImageGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CategoryImageGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryImageGui\Communication\Form\DataProvider\LocaleProvider
     */
    public function createLocaleProvider(): LocaleProvider
    {
        return new LocaleProvider(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface
     */
    public function getLocaleFacade(): CategoryImageGuiToLocaleInterface
    {
        return $this->getProvidedDependency(CategoryImageGuiDependencyProvider::FACADE_LOCALE);
    }
}
