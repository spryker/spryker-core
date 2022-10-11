<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface MessageDataFilterInterface
{
    /**
     * @param array<string, mixed> $messageData
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     *
     * @return array<string, mixed>
     */
    public function filter(array $messageData, AbstractTransfer $messageTransfer): array;
}
