<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ConfigurableBundleCartMapper implements ConfigurableBundleCartMapperInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig
     */
    protected $configurableBundleCartsRestApiConfig;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig $configurableBundleCartsRestApiConfig
     */
    public function __construct(ConfigurableBundleCartsRestApiConfig $configurableBundleCartsRestApiConfig)
    {
        $this->configurableBundleCartsRestApiConfig = $configurableBundleCartsRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    public function mapRestConfiguredBundlesAttributesToCreateConfiguredBundleRequest(
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer,
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): CreateConfiguredBundleRequestTransfer {
        $createConfiguredBundleRequestTransfer
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireTemplate()
                ->getTemplate()
                    ->requireUuid()
                    ->requireName();

        $configuredBundleTransfer = $createConfiguredBundleRequestTransfer
            ->getConfiguredBundle()
            ->setQuantity($restConfiguredBundlesAttributesTransfer->getQuantity());

        $createConfiguredBundleRequestTransfer = $createConfiguredBundleRequestTransfer
            ->setConfiguredBundle($configuredBundleTransfer);

        foreach ($restConfiguredBundlesAttributesTransfer->getItems() as $configuredBundleItemsAttributesTransfer) {
            $configuredBundleItemTransfer = (new ConfiguredBundleItemTransfer())
                ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($configuredBundleItemsAttributesTransfer->getSlotUuid()));

            $itemTransfer = (new ItemTransfer())
                ->fromArray($configuredBundleItemsAttributesTransfer->toArray(), true)
                ->setConfiguredBundleItem($configuredBundleItemTransfer);

            $createConfiguredBundleRequestTransfer->addItem($itemTransfer);
        }

        return $createConfiguredBundleRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapQuoteErrorTransferToRestErrorMessageTransfer(
        QuoteErrorTransfer $quoteErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer {
        $errorIdentifier = $quoteErrorTransfer->getErrorIdentifier();
        $errorIdentifierToRestErrorMapping = $this->configurableBundleCartsRestApiConfig->getErrorIdentifierToRestErrorMapping();

        if ($errorIdentifier && isset($errorIdentifierToRestErrorMapping[$errorIdentifier])) {
            $errorIdentifierMapping = $errorIdentifierToRestErrorMapping[$errorIdentifier];
            $restErrorMessageTransfer->fromArray($errorIdentifierMapping, true);

            return $restErrorMessageTransfer;
        }

        if ($quoteErrorTransfer->getMessage()) {
            return $this->createErrorMessageTransfer($quoteErrorTransfer);
        }

        return $restErrorMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(QuoteErrorTransfer $quoteErrorTransfer): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ConfigurableBundleCartsRestApiConfig::RESPONSE_CODE_CONFIGURED_BUNDLE_VALIDATION)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail($quoteErrorTransfer->getMessage());
    }
}
