<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Resolver;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_WISHLIST_PRODUCT_CONFIGURATION_NOT_FOUND = 'product_configuration_wishlist.error.configuration_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_ID = '%id%';

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface $wishlistClient
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface $productConfigurationClient
     */
    public function __construct(
        ProductConfigurationWishlistToWishlistClientInterface $wishlistClient,
        ProductConfigurationWishlistToProductConfigurationClientInterface $productConfigurationClient
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->productConfigurationClient = $productConfigurationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfiguratorRedirectTransfer = new ProductConfiguratorRedirectTransfer();
        $productConfiguratorRequestDataTransfer = $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail();

        $productConfigurationInstanceTransfer = $this->findWishlistItemProductConfigurationInstance($productConfiguratorRequestDataTransfer);

        if (!$productConfigurationInstanceTransfer) {
            return $this->addErrorToProductConfiguratorRedirect(
                $productConfiguratorRedirectTransfer,
                static::GLOSSARY_KEY_WISHLIST_PRODUCT_CONFIGURATION_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_ID => $productConfiguratorRequestDataTransfer->getIdWishlistItemOrFail()],
            );
        }

        $productConfiguratorRequestTransfer = $this->mapProductConfigurationInstanceTransferToProductConfiguratorRequestTransfer(
            $productConfigurationInstanceTransfer,
            $productConfiguratorRequestTransfer,
        );

        return $this->productConfigurationClient->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findWishlistItemProductConfigurationInstance(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ?ProductConfigurationInstanceTransfer {
        $wishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())
            ->setIdWishlistItem($productConfiguratorRequestDataTransfer->getIdWishlistItemOrFail());

        $wishlistItemTransfer = $this->wishlistClient
            ->getWishlistItem($wishlistItemCriteriaTransfer)
            ->getWishlistItem();

        if (!$wishlistItemTransfer || !$wishlistItemTransfer->getProductConfigurationInstance()) {
            return null;
        }

        return $wishlistItemTransfer->getProductConfigurationInstanceOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $configurationInstanceTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    protected function mapProductConfigurationInstanceTransferToProductConfiguratorRequestTransfer(
        ProductConfigurationInstanceTransfer $configurationInstanceTransfer,
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->fromArray(
            $configurationInstanceTransfer->toArray(),
            true,
        );

        return $productConfiguratorRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    protected function addErrorToProductConfiguratorRedirect(
        ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer,
        string $message,
        array $parameters = []
    ): ProductConfiguratorRedirectTransfer {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message)
            ->setParameters($parameters);

        return $productConfiguratorRedirectTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
