<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Adder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToConfigurableBundleStorageClientInterface;
use Spryker\Client\ConfigurableBundleCart\Generator\ConfiguredBundleGroupKeyGeneratorInterface;
use Spryker\Client\ConfigurableBundleCart\Validator\ConfiguredBundleValidatorInterface;

class ConfiguredBundleCartAdder implements ConfiguredBundleCartAdderInterface
{
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED = 'configured_bundle_cart.error.configured_bundle_cannot_be_added';

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Validator\ConfiguredBundleValidatorInterface
     */
    protected $configuredBundleValidator;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Generator\ConfiguredBundleGroupKeyGeneratorInterface
     */
    protected $configuredBundleGroupKeyGenerator;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface $cartClient
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     * @param \Spryker\Client\ConfigurableBundleCart\Validator\ConfiguredBundleValidatorInterface $configuredBundleValidator
     * @param \Spryker\Client\ConfigurableBundleCart\Generator\ConfiguredBundleGroupKeyGeneratorInterface $configuredBundleGroupKeyGenerator
     */
    public function __construct(
        ConfigurableBundleCartToCartClientInterface $cartClient,
        ConfigurableBundleCartToConfigurableBundleStorageClientInterface $configurableBundleStorageClient,
        ConfiguredBundleValidatorInterface $configuredBundleValidator,
        ConfiguredBundleGroupKeyGeneratorInterface $configuredBundleGroupKeyGenerator
    ) {
        $this->cartClient = $cartClient;
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
        $this->configuredBundleValidator = $configuredBundleValidator;
        $this->configuredBundleGroupKeyGenerator = $configuredBundleGroupKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundleToCart(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        if (!$this->configuredBundleValidator->validateCreateConfiguredBundleRequestTransfer($createConfiguredBundleRequestTransfer)) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED);
        }

        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient->findConfigurableBundleTemplateStorageByUuid(
            $createConfiguredBundleRequestTransfer->getConfiguredBundleRequest()->getTemplateUuid()
        );

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED);
        }

        $isTemplateSlotCombinationValid = $this->configuredBundleValidator->validateConfiguredBundleTemplateSlotCombination(
            $configurableBundleTemplateStorageTransfer,
            $createConfiguredBundleRequestTransfer->getConfiguredBundleItemRequests()
        );

        if (!$isTemplateSlotCombinationValid) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED);
        }

        $createConfiguredBundleRequestTransfer->getConfiguredBundleRequest()->setTemplateName(
            $configurableBundleTemplateStorageTransfer->getName()
        );

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
        $createConfiguredBundleRequestTransfer
            ->requireConfiguredBundleRequest()
            ->requireConfiguredBundleItemRequests();

        $configuredBundleRequestTransfer = $createConfiguredBundleRequestTransfer->getConfiguredBundleRequest();
        $configuredBundleRequestTransfer->setGroupKey(
            $this->configuredBundleGroupKeyGenerator->generateConfiguredBundleGroupKeyByUuid($configuredBundleRequestTransfer)
        );

        foreach ($createConfiguredBundleRequestTransfer->getConfiguredBundleItemRequests() as $configuredBundleItemRequestTransfer) {
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
            ->requireGroupKey()
            ->requireTemplateUuid()
            ->requireTemplateName();

        $configurableBundleTemplateTransfer = (new ConfigurableBundleTemplateTransfer())
            ->setUuid($configuredBundleRequestTransfer->getTemplateUuid())
            ->setName($configuredBundleRequestTransfer->getTemplateName());

        return $configuredBundleTransfer
            ->setTemplate($configurableBundleTemplateTransfer)
            ->setGroupKey($configuredBundleRequestTransfer->getGroupKey())
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
