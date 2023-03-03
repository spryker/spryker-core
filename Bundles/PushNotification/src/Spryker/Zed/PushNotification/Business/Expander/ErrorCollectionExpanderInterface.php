<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Expander;

use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface ErrorCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $extraErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function expandErrorCollection(
        ErrorCollectionTransfer $errorCollectionTransfer,
        ErrorCollectionTransfer $extraErrorCollectionTransfer
    ): ErrorCollectionTransfer;
}
