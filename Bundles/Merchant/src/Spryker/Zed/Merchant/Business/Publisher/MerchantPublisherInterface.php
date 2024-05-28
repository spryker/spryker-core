<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Publisher;

use Generated\Shared\Transfer\MerchantPublisherConfigTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface MerchantPublisherInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantPublisherConfigTransfer $merchantPublisherConfigTransfer
     *
     * @return void
     */
    public function publish(MerchantPublisherConfigTransfer $merchantPublisherConfigTransfer): void;
}
