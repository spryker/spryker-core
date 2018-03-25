<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer;

use Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Offer\OfferFactory getFactory()
 */
class OfferClient extends AbstractClient implements OfferClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $offerToOrderConvertRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(OfferToOrderConvertRequestTransfer $offerToOrderConvertRequestTransfer): OfferToOrderConvertResponseTransfer
    {
        return $this->getFactory()
            ->createZedStub()
            ->convertOfferToOrder($offerToOrderConvertRequestTransfer);
    }
}
