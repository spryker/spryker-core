<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\Twig;

use Spryker\Service\UtilText\Model\Url\Url;

class UrlFunction extends AbstractApplicationTwigFunction
{
    protected const ABSOLUTE_CUSTOM_OPTION = 'absolute';

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'url';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($url, array $query = [], array $options = []) {
            $url = Url::generate($url, $query, $this->formatOptions($options));

            return $url->buildEscaped();
        };
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function formatOptions(array $options): array
    {
        if (isset($options[static::ABSOLUTE_CUSTOM_OPTION]) && $options[static::ABSOLUTE_CUSTOM_OPTION] === true) {
            $options[Url::SCHEME] = $this->getScheme();
            $options[Url::HOST] = $this->getHttpHost();
            unset($options[static::ABSOLUTE_CUSTOM_OPTION]);
        }

        return $options;
    }

    /**
     * @return string
     */
    protected function getScheme(): string
    {
        return $this->getConfig()->isSslEnabled() ? 'https' : 'http';
    }

    /**
     * @return string
     */
    protected function getHttpHost(): string
    {
        return rtrim($this->getConfig()->getYvesHostName(), '/');
    }
}
