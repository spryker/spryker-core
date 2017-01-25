<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url\Parser;

use Spryker\Service\Url\Exception\UrlInvalidException;
use Spryker\Shared\Url\Url;

class UrlParser implements UrlParserInterface
{

    /**
     * @param string $url
     *
     * @throws \Spryker\Service\Url\Exception\UrlInvalidException
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    public function parse($url)
    {
        static $defaults = [
            Url::SCHEME => null,
            Url::HOST => null,
            Url::PORT => null,
            Url::USER => null,
            Url::PASS => null,
            Url::PATH => null,
            Url::QUERY => null,
            Url::FRAGMENT => null,
        ];

        $parts = parse_url($url);
        if ($parts === false) {
            throw new UrlInvalidException(sprintf('Was unable to parse malformed URL: %s', $url));
        }

        $parts += $defaults;

        return $this->createNewUrl($parts);
    }

    /**
     * @param array $parts
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    protected function createNewUrl(array $parts)
    {
        return new Url($parts);
    }

}
