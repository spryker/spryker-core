<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Http;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Symfony\Component\HttpFoundation\Request;

class HttpConfig extends AbstractSharedConfig
{
    protected const HTTP_FRAGMENT_PATH = '/_fragment';
    protected const REQUEST_TRUSTED_HEADER_SET = Request::HEADER_X_FORWARDED_ALL;

    /**
     * @return string
     */
    public function getUriSignerSecret(): string
    {
        return md5(__DIR__);
    }

    /**
     * @return string
     */
    public function getHttpFragmentPath(): string
    {
        return static::HTTP_FRAGMENT_PATH;
    }

    /**
     * @return string|null
     */
    public function getHIncludeRendererGlobalTemplate(): ?string
    {
        return null;
    }

    /**
     * @return int
     */
    public function getTrustedHeaderSet(): int
    {
        return static::REQUEST_TRUSTED_HEADER_SET;
    }
}
