<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Console\Business\Model;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleMessenger extends ConsoleLogger implements MessengerInterface
{
}
