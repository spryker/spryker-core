<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Adder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
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
        $createConfiguredBundleRequestTransfer
            ->requireItems()
            ->requireLocaleName()
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireTemplate()
                ->getTemplate()
                    ->requireUuid();

        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient->findConfigurableBundleTemplateStorageByUuid(
            $createConfiguredBundleRequestTransfer->getConfiguredBundle()->getTemplate()->getUuid(),
            $createConfiguredBundleRequestTransfer->getLocaleName()
        );

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED);
        }

        $isTemplateSlotCombinationValid = $this->configuredBundleValidator->validateConfiguredBundleTemplateSlotCombination(
            $configurableBundleTemplateStorageTransfer,
            $createConfiguredBundleRequestTransfer->getItems()
        );

        if (!$isTemplateSlotCombinationValid) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED);
        }

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
        $configuredBundleTransfer = $createConfiguredBundleRequestTransfer->getConfiguredBundle();

        $configuredBundleTransfer->setGroupKey(
            $this->configuredBundleGroupKeyGenerator->generateConfiguredBundleGroupKeyByUuid($configuredBundleTransfer)
        );

        $createConfiguredBundleRequestTransfer->setConfiguredBundle($configuredBundleTransfer);

        foreach ($createConfiguredBundleRequestTransfer->getItems() as $itemTransfer) {
            $cartChangeTransfer->addItem(
                $itemTransfer->setConfiguredBundle($configuredBundleTransfer)
            );
        }

        return $cartChangeTransfer;
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
