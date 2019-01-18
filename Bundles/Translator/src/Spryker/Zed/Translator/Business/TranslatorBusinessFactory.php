<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Spryker\Service\Translator\TranslatorServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Translator\TranslatorDependencyProvider;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Service\Translator\TranslatorServiceInterface
     */
    public function getTranslatorService(): TranslatorServiceInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::SERVICE_TRANSLATOR);
    }
}
