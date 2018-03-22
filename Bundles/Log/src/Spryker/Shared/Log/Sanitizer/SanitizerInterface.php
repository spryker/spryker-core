<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Sanitizer;

interface SanitizerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data);

    /**
     * Sanitize value if key match against given sanitize keys.
     *
     * @param mixed $value
     * @param string $key
     *
     * @return mixed
     */
    public function sanitizeValue($value, $key);
}
