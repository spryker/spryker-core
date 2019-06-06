<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\Twig;

use Spryker\Service\UtilText\Model\Url\Url;

class AssetsUrlFunction extends AbstractApplicationTwigFunction
{
    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'assets_url';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($path) {
            $path = ltrim($path, '/');
            $url = Url::generate($this->getAssetsPath() . $path, [], $this->getOptions());

            return $url->buildEscaped();
        };
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        $options[Url::SCHEME] = $this->getScheme();
        $options[Url::HOST] = $this->getHttpHost();

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

    /**
     * @return string
     */
    protected function getAssetsPath(): string
    {
        return rtrim($this->getConfig()->getAssetsPath(), '/') . '/';
    }
}
