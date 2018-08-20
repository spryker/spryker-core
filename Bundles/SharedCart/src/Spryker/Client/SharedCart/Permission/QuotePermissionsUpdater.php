<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Permission;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;

class QuotePermissionsUpdater implements QuotePermissionsUpdaterInterface
{
    /**
     * TODO: Update this method with logic.
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuotePermissions(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer
    {
        return new QuoteResponseTransfer();
    }
}
