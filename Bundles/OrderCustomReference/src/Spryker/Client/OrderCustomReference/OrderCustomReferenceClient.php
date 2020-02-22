<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OrderCustomReference\OrderCustomReferenceFactory getFactory()
 */
class OrderCustomReferenceClient extends AbstractClient implements OrderCustomReferenceClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(?string $orderCustomReference): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createOrderCustomReferenceSetter()
            ->setOrderCustomReference($orderCustomReference);
    }
}
