<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Reader;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;

class QuoteItemReader implements QuoteItemReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function getItemsByConfiguredBundleGroupKey(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): CartChangeTransfer
    {
        $updateConfiguredBundleRequestTransfer
            ->requireGroupKey()
            ->requireQuote();

        $cartChangeTransfer = new CartChangeTransfer();
        $configuredBundleGroupKey = $updateConfiguredBundleRequestTransfer->getGroupKey();

        foreach ($updateConfiguredBundleRequestTransfer->getQuote()->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle() && $itemTransfer->getConfiguredBundle()->getGroupKey() === $configuredBundleGroupKey) {
                $cartChangeTransfer->addItem($itemTransfer);
            }
        }

        return $cartChangeTransfer;
    }
}
