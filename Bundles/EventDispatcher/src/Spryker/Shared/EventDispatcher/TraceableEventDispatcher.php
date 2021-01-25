<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventDispatcher;

use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher as SymfonyTraceableEventDispatcher;

class TraceableEventDispatcher extends SymfonyTraceableEventDispatcher implements EventDispatcherInterface
{
}
