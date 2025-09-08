<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\MessageBroker;

use Generated\Shared\Transfer\CmsPageMessageBrokerRequestTransfer;

interface CmsPageMessageBrokerPublisherInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsPageMessageBrokerRequestTransfer $cmsPageMessageBrokerRequestTransfer
     *
     * @return void
     */
    public function sendCmsPagesToMessageBroker(CmsPageMessageBrokerRequestTransfer $cmsPageMessageBrokerRequestTransfer): void;
}
