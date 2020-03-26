<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;

class DiscountMapper implements DiscountMapperInterface
{
    /**
     * @var \Spryker\Glue\CartCodesRestApiExtension\Dependency\Plugin\DiscountMapperPluginInterface[]
     */
    protected $discountMapperPlugins;

    /**
     * @param \Spryker\Glue\CartCodesRestApiExtension\Dependency\Plugin\DiscountMapperPluginInterface[] $discountMapperPlugins
     */
    public function __construct(array $discountMapperPlugins)
    {
        $this->discountMapperPlugins = $discountMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestDiscountsAttributesTransfer
     */
    public function mapDiscountDataToRestDiscountsAttributesTransfer(
        DiscountTransfer $discountTransfer,
        RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
    ): RestDiscountsAttributesTransfer {
        $restDiscountsAttributesTransfer
            ->fromArray($discountTransfer->toArray(), true)
            ->setCode($discountTransfer->getVoucherCode())
            ->setExpirationDateTime($discountTransfer->getValidTo());

        foreach ($this->discountMapperPlugins as $discountMapperPlugin) {
            $restDiscountsAttributesTransfer = $discountMapperPlugin->mapDiscountTransferToRestDiscountsAttributesTransfer(
                $discountTransfer,
                $restDiscountsAttributesTransfer
            );
        }

        return $restDiscountsAttributesTransfer;
    }
}
