<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url\Parser;

interface UrlParserInterface
{

    /**
     * @param string $url
     *
     * @throws \Spryker\Service\Url\Exception\UrlInvalidException
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    public function parse($url);

}
