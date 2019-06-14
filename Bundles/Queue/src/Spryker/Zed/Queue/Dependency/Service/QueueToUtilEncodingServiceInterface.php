<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Dependency\Service;

interface QueueToUtilEncodingServiceInterface
{
    /**
     * @param array $data
     * @param string $format
     *
     * @return string|null
     */
    public function encodeToFormat(array $data, string $format): ?string;
}
