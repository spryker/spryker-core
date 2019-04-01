<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorageExtension\Dependency\Plugin;

/**
 * @deprecated Will be removed without replacement.
 */
interface ContentTermExecutorPluginInterface
{
    /**
     * Specification:
     * - Runs after storage client
     * - Returns processed parameters.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return array
     */
    public function execute(array $parameters): array;

    /**
     * Specification:
     * - Returns term key.
     *
     * @api
     *
     * @return string
     */
    public function getTermKey(): string;

    /**
     * Specification:
     * - Returns type key.
     *
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string;
}
