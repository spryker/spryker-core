<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;

class CheckoutResponseMapper implements CheckoutResponseMapperInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutResponseMapperPluginInterface[]
     */
    protected $checkoutResponseMapperPlugins;

    /**
     * @param array $checkoutResponseMapperPlugins
     */
    public function __construct(array $checkoutResponseMapperPlugins)
    {
        $this->checkoutResponseMapperPlugins = $checkoutResponseMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutResponseTransfer $restCheckoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer
     */
    public function mapRestCheckoutResponseTransferToRestCheckoutResponseAttributesTransfer(
        RestCheckoutResponseTransfer $restCheckoutResponseTransfer
    ): RestCheckoutResponseAttributesTransfer {
        $restCheckoutResponseAttributesTransfer = (new RestCheckoutResponseAttributesTransfer())
            ->setOrderReference($restCheckoutResponseTransfer->getOrderReference());

        foreach ($this->checkoutResponseMapperPlugins as $checkoutResponseMapperPlugin) {
            $restCheckoutResponseAttributesTransfer = $checkoutResponseMapperPlugin
                ->mapRestCheckoutResponseTransferToRestCheckoutResponseAttributesTransfer(
                    $restCheckoutResponseTransfer,
                    $restCheckoutResponseAttributesTransfer
                );
        }

        return $restCheckoutResponseAttributesTransfer;
    }
}
