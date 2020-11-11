<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequestExtension\Dependency\Plugin;

/**
 * Allows to add addition header to be used in a ZedRequest.
 * Use this plugin if you need additional header for the ZedRequest.
 */
interface HeaderExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds additional header to be used in a ZedRequest.
     *
     * @api
     *
     * @param array $header
     *
     * @return array
     */
    public function expandHeader(array $header): array;
}
