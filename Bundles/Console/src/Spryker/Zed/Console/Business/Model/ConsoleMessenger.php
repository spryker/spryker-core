<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Console\Business\Model;

use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleMessenger extends ConsoleLogger implements MessengerInterface
{
}
