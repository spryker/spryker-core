<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\GiftCardBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class GiftCardHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function haveGiftCard(array $seedData = [])
    {
        $giftCardTransfer = (new GiftCardBuilder($seedData))->build();
        $giftCardTransfer->setIdGiftCard(null);

        $this->getLocator()->giftCard()->facade()->create($giftCardTransfer);

        return $giftCardTransfer;
    }
}
