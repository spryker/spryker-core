<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Finisher;

use Generated\Shared\Transfer\CustomerTransfer;

interface ImpersonationFinisherInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function finishImpersonation(CustomerTransfer $customerTransfer): void;
}
