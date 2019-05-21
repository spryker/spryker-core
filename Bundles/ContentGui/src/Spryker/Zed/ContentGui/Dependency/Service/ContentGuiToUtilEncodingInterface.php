<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Dependency\Service;

interface ContentGuiToUtilEncodingInterface
{
    /**
     * @param mixed $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson($value, $options = null, $depth = null): ?string;

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array
     */
    public function decodeJson(string $jsonValue, bool $assoc = false, ?int $depth = null, ?int $options = null);
}
