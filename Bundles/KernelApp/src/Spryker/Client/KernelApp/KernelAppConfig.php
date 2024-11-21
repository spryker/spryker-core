<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\KernelApp\KernelAppConstants;

/**
 * @method \Spryker\Shared\KernelApp\KernelAppConfig getSharedConfig()
 */
class KernelAppConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const HEADER_TENANT_IDENTIFIER = 'x-tenant-identifier';

    /**
     * Specification:
     * - Returns an array of default headers to be used in HTTP requests.
     * - The array keys represent the header names.
     * - The array values represent the header values.
     * - The default headers include a 'x-tenant-identifier' header that merges the shared headers.
     * - If a header already exists in the request, it will not be overridden.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getDefaultHeaders(): array
    {
        return array_merge($this->getSharedConfig()->getDefaultHeaders(), [
            static::HEADER_TENANT_IDENTIFIER => $this->getTenantIdentifier(),
        ]);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(KernelAppConstants::TENANT_IDENTIFIER);
    }
}
