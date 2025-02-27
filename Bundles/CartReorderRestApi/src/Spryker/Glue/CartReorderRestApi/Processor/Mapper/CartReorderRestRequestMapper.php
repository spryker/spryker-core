<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;

class CartReorderRestRequestMapper implements CartReorderRestRequestMapperInterface
{
    /**
     * @param list<\Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\RestCartReorderAttributesMapperPluginInterface> $restCartReorderAttributesMapperPlugins
     */
    public function __construct(protected array $restCartReorderAttributesMapperPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function mapRestCartReorderRequestAttributesToCartReorderRequest(
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer {
        $cartReorderRequestTransfer = $cartReorderRequestTransfer
            ->setOrderReference($restCartReorderRequestAttributesTransfer->getOrderReferenceOrFail());

        return $this->executeRestCartReorderAttributesMapperPlugins($restCartReorderRequestAttributesTransfer, $cartReorderRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    protected function executeRestCartReorderAttributesMapperPlugins(
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer {
        foreach ($this->restCartReorderAttributesMapperPlugins as $restCartReorderAttributesMapperPlugin) {
            $cartReorderRequestTransfer = $restCartReorderAttributesMapperPlugin
                ->mapRestCartReorderRequestAttributesToCartReorderRequestTransfer(
                    $restCartReorderRequestAttributesTransfer,
                    $cartReorderRequestTransfer,
                );
        }

        return $cartReorderRequestTransfer;
    }
}
