<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilSanitize\Model\ArrayFilter;
use Spryker\Service\UtilSanitize\Model\Html;
use Spryker\Service\UtilSanitize\Model\StringSanitizer;
use Spryker\Service\UtilSanitize\Model\StringSanitizerInterface;

/**
 * @method \Spryker\Service\UtilSanitize\UtilSanitizeConfig getConfig()
 */
class UtilSanitizeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilSanitize\Model\HtmInterface
     */
    public function createHtml()
    {
        return new Html();
    }

    /**
     * @return \Spryker\Service\UtilSanitize\Model\ArrayFilterInterface
     */
    public function createArrayFilter()
    {
        return new ArrayFilter();
    }

    /**
     * @return \Spryker\Service\UtilSanitize\Model\StringSanitizerInterface
     */
    public function createStringSanitizer(): StringSanitizerInterface
    {
        return new StringSanitizer(
            $this->getStringSanitizerPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilSanitizeExtension\Dependency\Plugin\StringSanitizerPluginInterface[]
     */
    public function getStringSanitizerPlugins(): array
    {
        return $this->getProvidedDependency(UtilSanitizeDependencyProvider::PLUGINS_STRING_SANITIZER);
    }
}
