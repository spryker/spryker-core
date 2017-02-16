<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Adapter;

use Spryker\Shared\Queue\ConnectionInterface;
use Spryker\Shared\Queue\ConsumerInterface;
use Spryker\Shared\Queue\PublisherInterface;

interface AdapterInterface extends ConnectionInterface, ConsumerInterface, PublisherInterface
{
}
