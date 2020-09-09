<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Generator\ConfiguredBundleGroupKeyGeneratorInterface;

class ConfiguredBundleMapper implements ConfiguredBundleMapperInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Generator\ConfiguredBundleGroupKeyGeneratorInterface
     */
    protected $configuredBundleGroupKeyGenerator;

    /**
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Generator\ConfiguredBundleGroupKeyGeneratorInterface $configuredBundleGroupKeyGenerator
     */
    public function __construct(ConfiguredBundleGroupKeyGeneratorInterface $configuredBundleGroupKeyGenerator)
    {
        $this->configuredBundleGroupKeyGenerator = $configuredBundleGroupKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCreateConfiguredBundleRequestToQuote(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $quoteTransfer
            ->setUuid($createConfiguredBundleRequestTransfer->getQuoteUuid())
            ->setCustomerReference($createConfiguredBundleRequestTransfer->getCustomer()->getCustomerReference())
            ->setCustomer($createConfiguredBundleRequestTransfer->getCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCreateConfiguredBundleRequestToPersistentCartChange(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        $itemTransfers = [];
        $configuredBundleTransfer = $this->getSlimConfiguredBundleTransfer($createConfiguredBundleRequestTransfer->getConfiguredBundle());

        foreach ($createConfiguredBundleRequestTransfer->getItems() as $itemTransfer) {
            $itemTransfers[] = $this->getSlimItemTransfer($configuredBundleTransfer, $itemTransfer);
        }

        $persistentCartChangeTransfer->setItems(new ArrayObject($itemTransfers));

        return $persistentCartChangeTransfer;
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

        $configuredBundleGroupKey = $this->configuredBundleGroupKeyGenerator->generateConfiguredBundleGroupKeyByUuid($configuredBundleTransfer);

        return (new ConfiguredBundleTransfer())
            ->setGroupKey($configuredBundleGroupKey)
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
}
