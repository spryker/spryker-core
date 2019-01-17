<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

abstract class AbstractButtonCreator
{
    /**
     * @param string $url
     * @param string $title
     * @param array $defaultOptions
     * @param array|null $customOptions
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function generateButtonTransfer(string $url, string $title, array $defaultOptions, ?array $customOptions = null): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle($title)
            ->setDefaultOptions($defaultOptions)
            ->setCustomOptions($customOptions);
    }

    /**
     * @param string $url
     * @param array $queryParams
     *
     * @return string
     */
    protected function generateUrl(string $url, array $queryParams): string
    {
        return Url::generate($url, $queryParams);
    }
}
