<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Expander;

use Generated\Shared\Transfer\MessageAttributesTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface CurrentStoreReferenceMessageAttributesExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expand(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer;
}
