<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Adapter;

use Spryker\Client\Queue\Model\Internal\ManagerInterface;
use Spryker\Client\Queue\Model\Internal\ReceiverInterface;
use Spryker\Client\Queue\Model\Internal\SenderInterface;

interface AdapterInterface extends ReceiverInterface, SenderInterface, ManagerInterface
{
}
