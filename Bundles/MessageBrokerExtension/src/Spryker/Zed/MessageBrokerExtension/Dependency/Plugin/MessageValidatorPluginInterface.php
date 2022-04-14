<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerExtension\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MessageValidatorPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValid(TransferInterface $messageTransfer): bool;
}
