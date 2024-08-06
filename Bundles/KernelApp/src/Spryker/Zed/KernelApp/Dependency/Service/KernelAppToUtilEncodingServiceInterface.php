<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Dependency\Service;

interface KernelAppToUtilEncodingServiceInterface
{
    /**
     * @param array<string, mixed> $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson(array $value, ?int $options = null, ?int $depth = null): ?string;
}
