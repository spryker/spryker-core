<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeInterface;
use Spryker\Zed\Translator\TranslatorDependencyProvider;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class TranslatorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::APPLICATION);
    }

    /**
     * @return \Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): TranslatorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::FACADE_LOCALE);
    }
}
