<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Config;

interface ConfigFormatterInterface
{
    /**
     * @param string $config
     *
     * @return array<string, mixed>
     */
    public function format(string $config): array;
}
