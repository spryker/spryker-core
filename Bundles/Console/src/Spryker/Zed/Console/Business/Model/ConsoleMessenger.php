<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Business\Model;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleMessenger extends ConsoleLogger implements MessengerInterface
{
}
