<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency;

use Generated\Shared\Transfer\QuoteTransfer;

interface PostSavePluginInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer $quoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer);
}
