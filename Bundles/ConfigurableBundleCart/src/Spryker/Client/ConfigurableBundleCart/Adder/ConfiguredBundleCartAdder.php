<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Adder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Dependency\Service\ConfigurableBundleCartToConfigurableBundleCartServiceInterface;

class ConfiguredBundleCartAdder implements ConfiguredBundleCartAdderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Service\ConfigurableBundleCartToConfigurableBundleCartServiceInterface
     */
    protected $configurableBundleCartService;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface $cartClient
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Service\ConfigurableBundleCartToConfigurableBundleCartServiceInterface $configurableBundleCartService
     */
    public function __construct(
        ConfigurableBundleCartToCartClientInterface $cartClient,
        ConfigurableBundleCartToConfigurableBundleCartServiceInterface $configurableBundleCartService
    ) {
        $this->cartClient = $cartClient;
        $this->configurableBundleCartService = $configurableBundleCartService;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundleToCart(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        $createConfiguredBundleRequestTransfer
            ->requireItems()
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireTemplate()
                ->getTemplate()
                    ->requireUuid();

        $cartChangeTransfer = $this->mapCreateConfiguredBundleRequestTransferToCartChangeTransfer(
            $createConfiguredBundleRequestTransfer,
            new CartChangeTransfer()
        );

        return $this->cartClient->addToCart($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function mapCreateConfiguredBundleRequestTransferToCartChangeTransfer(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer {
        $configuredBundleTransfer = $this->getSlimConfiguredBundleTransfer($createConfiguredBundleRequestTransfer->getConfiguredBundle());

        foreach ($createConfiguredBundleRequestTransfer->getItems() as $itemTransfer) {
            $cartChangeTransfer->addItem($this->getSlimItemTransfer($configuredBundleTransfer, $itemTransfer));
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function getSlimConfiguredBundleTransfer(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        $configuredBundleTransfer
            ->requireQuantity()
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid();

        $configuredBundleTransfer = $this->configurableBundleCartService->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);

        return (new ConfiguredBundleTransfer())
            ->setGroupKey($configuredBundleTransfer->getGroupKey())
            ->setQuantity($configuredBundleTransfer->getQuantity())
            ->setTemplate(
                (new ConfigurableBundleTemplateTransfer())
                    ->setUuid($configuredBundleTransfer->getTemplate()->getUuid())
                    ->setName($configuredBundleTransfer->getTemplate()->getName())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getSlimItemTransfer(ConfiguredBundleTransfer $configuredBundleTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer
            ->getConfiguredBundleItem()
                ->requireSlot()
                ->getSlot()
                    ->requireUuid();

        $configuredBundleItemTransfer = (new ConfiguredBundleItemTransfer())
            ->setQuantityPerSlot($itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot())
            ->setSlot(
                (new ConfigurableBundleTemplateSlotTransfer())
                    ->setUuid($itemTransfer->getConfiguredBundleItem()->getSlot()->getUuid())
            );

        $itemTransfer
            ->setConfiguredBundle($configuredBundleTransfer)
            ->setConfiguredBundleItem($configuredBundleItemTransfer);

        return $itemTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createErrorResponse(string $message): QuoteResponseTransfer
    {
        $quoteErrorTransfer = (new QuoteErrorTransfer())
            ->setMessage($message);

        return (new QuoteResponseTransfer())
            ->addError($quoteErrorTransfer);
    }
}
