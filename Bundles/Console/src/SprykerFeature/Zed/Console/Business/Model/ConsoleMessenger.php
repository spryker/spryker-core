<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleMessenger extends ConsoleLogger implements MessengerInterface
{
}
