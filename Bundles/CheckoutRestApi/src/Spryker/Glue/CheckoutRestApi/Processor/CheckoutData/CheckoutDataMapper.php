<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

class CheckoutDataMapper implements CheckoutDataMapperInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface[]
     */
    protected $checkoutDataResponseMappers;

    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataResponseMapperPluginInterface[]
     */
    protected $checkoutDataResponseMapperPlugins;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface[] $checkoutDataResponseMappers
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataResponseMapperPluginInterface[] $checkoutDataResponseMapperPlugins
     */
    public function __construct(
        array $checkoutDataResponseMappers,
        array $checkoutDataResponseMapperPlugins
    ) {
        $this->checkoutDataResponseMappers = $checkoutDataResponseMappers;
        $this->checkoutDataResponseMapperPlugins = $checkoutDataResponseMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $restCheckoutDataResponseAttributesTransfer = new RestCheckoutDataResponseAttributesTransfer();

        foreach ($this->checkoutDataResponseMappers as $checkoutDataResponseMapper) {
            $restCheckoutDataResponseAttributesTransfer = $checkoutDataResponseMapper->map(
                $restCheckoutDataTransfer,
                $restCheckoutDataResponseAttributesTransfer
            );
        }

        foreach ($this->checkoutDataResponseMapperPlugins as $checkoutDataResponseMapperPlugin) {
            $restCheckoutDataResponseAttributesTransfer = $checkoutDataResponseMapperPlugin->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataTransfer,
                $restCheckoutRequestAttributesTransfer,
                $restCheckoutDataResponseAttributesTransfer
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }
}
