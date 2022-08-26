<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Expander;

use Spryker\Shared\EventDispatcher\EventDispatcherInterface;

interface SecurityHeaderExpanderInterface
{
    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function expand(EventDispatcherInterface $eventDispatcher): EventDispatcherInterface;
}
