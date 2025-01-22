<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Business\Filter;

interface MessageChannelFilterInterface
{
    /**
     * @param list<string> $messageChannelNames
     *
     * @return list<string>
     */
    public function filterMessageChannels(array $messageChannelNames): array;
}
