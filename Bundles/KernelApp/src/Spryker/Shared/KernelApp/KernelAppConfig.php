<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\KernelApp;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Symfony\Component\HttpFoundation\Request;

class KernelAppConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    protected const COOKIE_HEADER = 'Cookie';

    /**
     * @var string
     */
    protected const CONTENT_TYPE_HEADER = 'Content-Type';

    /**
     * @var string
     */
    protected const CONTENT_TYPE_HEADER_VALUE = 'application/json';

    /**
     * Specification:
     * - Returns an array of default headers to be used in HTTP requests.
     * - The array keys represent the header names.
     * - The array values represent the header values.
     * - The default headers include a 'Content-Type' header with the value 'application/json' and Cookie for XDebug session.
     * - If a header already exists in the request, it will not be overridden.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getDefaultHeaders(): array
    {
        $headers = [
            static::CONTENT_TYPE_HEADER => static::CONTENT_TYPE_HEADER_VALUE,
        ];

        $request = Request::createFromGlobals();

        if ($request->cookies->has('XDEBUG_SESSION')) {
            $headers[static::COOKIE_HEADER] = sprintf('XDEBUG_SESSION=%s', $request->cookies->get('XDEBUG_SESSION'));
        }

        if ($this->isForwardDebugSessionEnabled()) {
            $headers[static::COOKIE_HEADER] = 'XDEBUG_SESSION=PHPSTORM';
        }

        return $headers;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isForwardDebugSessionEnabled(): bool
    {
        return $this->getConfig()->get(KernelAppConstants::ENABLE_FORWARD_DEBUG_SESSION, false);
    }
}
