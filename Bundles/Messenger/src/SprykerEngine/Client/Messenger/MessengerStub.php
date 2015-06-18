<?php

/**
 * (c) Spryker_Systems_GmbH_copyright_protected
 */

/**
 * Spryker Framework.
 *
 * (c) Spryker Systems GmbH <info@spryker.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SprykerEngine\Client\Messenger;

use SprykerEngine\Client\Kernel\AbstractStub;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerStub extends AbstractStub
{
    /**
     * @return MessengerInterface
     */
    public function createMessenger()
    {
        return $this->getDependencyContainer()->createMessenger();
    }
}
