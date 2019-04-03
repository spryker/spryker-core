<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;


use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutResponseMapperPluginInterface;

class TestRestCheckoutResponseMapperPlugin implements CheckoutResponseMapperPluginInterface
{
    public function mapRestCheckoutResponseTransferToRestCheckoutResponseAttributesTransfer(RestCheckoutResponseTransfer $restCheckoutResponseTransfer, RestCheckoutResponseAttributesTransfer $restCheckoutResponseAttributesTransfer): RestCheckoutResponseAttributesTransfer
    {
        $restCheckoutResponseTransfer->getErrors()->append(
            (new RestCheckoutErrorTransfer())
                ->setCode(777)
                ->setStatus(200)
                ->setDetail('Kabala says You look handsome!')
        );
    }
}