<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize\Model;

use Spryker\Service\UtilSanitize\UtilSanitizeConfig;

class StringSanitizer implements StringSanitizerInterface
{
    /**
     * @var \Spryker\Service\UtilSanitizeExtension\Dependency\Plugin\StringSanitizerPluginInterface[]
     */
    protected $sanitizerPlugins;

    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\UtilSanitizeExtension\Dependency\Plugin\StringSanitizerPluginInterface[] $sanitizerPlugins
     * @param \Spryker\Service\UtilSanitize\UtilSanitizeConfig $config
     */
    public function __construct(array $sanitizerPlugins, UtilSanitizeConfig $config)
    {
        $this->sanitizerPlugins = $sanitizerPlugins;
        $this->config = $config;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function sanitize(string $value): string
    {
        $replacement = $this->config->getStringSanitizerReplacement();

        foreach ($this->sanitizerPlugins as $sanitizerPlugin) {
            $value = $sanitizerPlugin->sanitize($value, $replacement);
        }

        return $value;
    }
}
