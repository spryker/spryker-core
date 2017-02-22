<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Dependency\Plugin\ItemCountPluginInterface;

class ItemCountPlugin implements ItemCountPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getItemCount(QuoteTransfer $quoteTransfer)
    {
        $quantity = $quoteTransfer
            ->getItems()
            ->count();

        return $quantity;
    }

}
