<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class KernelAppConfig extends AbstractBundleConfig
{
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
     * - The default headers include a 'Content-Type' header with the value 'application/json'.
     * - If a header already exists in the request, it will not be overridden.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getDefaultHeaders(): array
    {
        return [
            static::CONTENT_TYPE_HEADER => static::CONTENT_TYPE_HEADER_VALUE,
        ];
    }
}
