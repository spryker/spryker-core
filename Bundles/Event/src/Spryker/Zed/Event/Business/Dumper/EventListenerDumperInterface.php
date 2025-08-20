<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Dumper;

interface EventListenerDumperInterface
{
    /**
     * @return array<string, array<string, array<string, mixed>>>
     */
    public function dump(): array;
}
