<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Mapper;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\ConfigurableBundleCart\Generator\ConfiguredBundleGroupKeyGeneratorInterface;

class ConfiguredBundleMapper implements ConfiguredBundleMapperInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Generator\ConfiguredBundleGroupKeyGeneratorInterface
     */
    protected $configuredBundleGroupKeyGenerator;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Generator\ConfiguredBundleGroupKeyGeneratorInterface $configuredBundleGroupKeyGenerator
     */
    public function __construct(ConfiguredBundleGroupKeyGeneratorInterface $configuredBundleGroupKeyGenerator)
    {
        $this->configuredBundleGroupKeyGenerator = $configuredBundleGroupKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function mapCreateConfiguredBundleRequestTransferToCartChangeTransfer(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer {
        $createConfiguredBundleRequestTransfer
            ->requireConfiguredBundleRequest()
            ->requireItems();

        $configuredBundleRequestTransfer = $createConfiguredBundleRequestTransfer->getConfiguredBundleRequest();

        foreach ($createConfiguredBundleRequestTransfer->getItems() as $configuredBundleItemRequestTransfer) {
            $cartChangeTransfer->addItem(
                $this->createItemTransferWithConfiguredBundle($configuredBundleRequestTransfer, $configuredBundleItemRequestTransfer)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer $configuredBundleItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferWithConfiguredBundle(
        ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer,
        ConfiguredBundleItemRequestTransfer $configuredBundleItemRequestTransfer
    ): ItemTransfer {
        $configuredBundleItemRequestTransfer
            ->requireSku()
            ->requireQuantity();

        $configuredBundleTransfer = $this->mapConfiguredBundleRequestTransferToConfiguredBundleTransfer(
            $configuredBundleRequestTransfer,
            new ConfiguredBundleTransfer()
        );

        $configuredBundleItemTransfer = $this->mapConfiguredBundleItemRequestTransferToConfiguredBundleItemTransfer(
            $configuredBundleItemRequestTransfer,
            new ConfiguredBundleItemTransfer()
        );

        return (new ItemTransfer())
            ->fromArray($configuredBundleItemRequestTransfer->toArray(), true)
            ->setConfiguredBundle($configuredBundleTransfer)
            ->setConfiguredBundleItem($configuredBundleItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function mapConfiguredBundleRequestTransferToConfiguredBundleTransfer(
        ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer,
        ConfiguredBundleTransfer $configuredBundleTransfer
    ): ConfiguredBundleTransfer {
        $configuredBundleRequestTransfer
            ->requireQuantity()
            ->requireTemplateUuid()
            ->requireTemplateName();

        $groupKey = $this->configuredBundleGroupKeyGenerator->generateConfiguredBundleGroupKeyByUuid($configuredBundleRequestTransfer);

        $configurableBundleTemplateTransfer = (new ConfigurableBundleTemplateTransfer())
            ->setUuid($configuredBundleRequestTransfer->getTemplateUuid())
            ->setName($configuredBundleRequestTransfer->getTemplateName());

        return $configuredBundleTransfer
            ->setTemplate($configurableBundleTemplateTransfer)
            ->setGroupKey($groupKey)
            ->setQuantity($configuredBundleRequestTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer $configuredBundleItemRequestTransfer
     * @param \Generated\Shared\Transfer\ConfiguredBundleItemTransfer $configuredBundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleItemTransfer
     */
    protected function mapConfiguredBundleItemRequestTransferToConfiguredBundleItemTransfer(
        ConfiguredBundleItemRequestTransfer $configuredBundleItemRequestTransfer,
        ConfiguredBundleItemTransfer $configuredBundleItemTransfer
    ): ConfiguredBundleItemTransfer {
        $configuredBundleItemRequestTransfer->requireSlotUuid();

        $configurableBundleTemplateSlotTransfer = (new ConfigurableBundleTemplateSlotTransfer())
            ->setUuid($configuredBundleItemRequestTransfer->getSlotUuid());

        return $configuredBundleItemTransfer->setSlot($configurableBundleTemplateSlotTransfer);
    }
}
