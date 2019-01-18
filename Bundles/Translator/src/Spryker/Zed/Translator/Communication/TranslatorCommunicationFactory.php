<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication;

use Spryker\Service\Translator\TranslatorServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Translator\TranslatorDependencyProvider;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class TranslatorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\Translator\TranslatorServiceInterface
     */
    public function getTranslatorService(): TranslatorServiceInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::SERVICE_TRANSLATOR);
    }
}
