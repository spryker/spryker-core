<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidatorInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidatorInterface
     */
    protected $productConfiguratorResponseValidator;

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface $productConfigurationClient
     * @param \Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface $wishlistClient
     */
    public function __construct(
        ProductConfigurationWishlistToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator,
        ProductConfigurationWishlistToWishlistClientInterface $wishlistClient
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
        $this->productConfiguratorResponseValidator = $productConfiguratorResponseValidator;
        $this->wishlistClient = $wishlistClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseTransfer = $this->productConfigurationClient->mapProductConfiguratorCheckSumResponse(
            $configuratorResponseData,
            $productConfiguratorResponseTransfer,
        );

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true)
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer);

        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfiguratorResponseValidator
            ->validateProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseProcessorResponseTransfer,
                $configuratorResponseData,
            );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $this->updateWishlistItemProductConfiguration($productConfiguratorResponseProcessorResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function updateWishlistItemProductConfiguration(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $wishlistItemTransfer = $this->createWishlistItemFromResponse(
            $productConfiguratorResponseProcessorResponseTransfer,
        );

        $wishlistItemResponseTransfer = $this->wishlistClient->updateWishlistItem($wishlistItemTransfer);

        if ($wishlistItemResponseTransfer->getIsSuccess()) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->setIsSuccessful(true)
                ->setWishlistName($wishlistItemResponseTransfer->getWishlistItemOrFail()->getWishlistName());
        }

        return $this->addWishlistItemErrorMessages(
            $wishlistItemResponseTransfer,
            $productConfiguratorResponseProcessorResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createWishlistItemFromResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): WishlistItemTransfer {
        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer->getProductConfiguratorResponseOrFail();

        return (new WishlistItemTransfer())
            ->setSku($productConfiguratorResponseTransfer->getSkuOrFail())
            ->setIdWishlistItem((int)$productConfiguratorResponseTransfer->getIdWishlistItemOrFail())
            ->setProductConfigurationInstance($productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemResponseTransfer $wishlistItemResponseTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function addWishlistItemErrorMessages(
        WishlistItemResponseTransfer $wishlistItemResponseTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        foreach ($wishlistItemResponseTransfer->getMessages() as $messageTransfer) {
            $productConfiguratorResponseProcessorResponseTransfer->addMessage($messageTransfer);
        }

        return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful(false);
    }
}
