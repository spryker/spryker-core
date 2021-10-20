<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Resolver;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToQuoteClientInterface;
use Spryker\Client\ProductConfigurationCart\Reader\ProductConfigurationInstanceQuoteReaderInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_FOUND = 'product_configuration.error.configuration_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\ProductConfigurationCart\Reader\ProductConfigurationInstanceQuoteReaderInterface
     */
    protected $productConfigurationInstanceQuoteReader;

    /**
     * @param \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationClientInterface $productConfigurationClient
     * @param \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\ProductConfigurationCart\Reader\ProductConfigurationInstanceQuoteReaderInterface $productConfigurationInstanceQuoteReader
     */
    public function __construct(
        ProductConfigurationCartToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfigurationCartToQuoteClientInterface $quoteClient,
        ProductConfigurationInstanceQuoteReaderInterface $productConfigurationInstanceQuoteReader
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
        $this->quoteClient = $quoteClient;
        $this->productConfigurationInstanceQuoteReader = $productConfigurationInstanceQuoteReader;
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

        $productConfigurationInstanceTransfer = $this->productConfigurationInstanceQuoteReader->findProductConfigurationInstanceInQuote(
            $productConfiguratorRequestDataTransfer->getItemGroupKeyOrFail(),
            $productConfiguratorRequestDataTransfer->getSkuOrFail(),
            $this->quoteClient->getQuote()
        );

        if (!$productConfigurationInstanceTransfer) {
            return $this->addErrorToProductConfiguratorRedirect(
                $productConfiguratorRedirectTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_SKU => $productConfiguratorRequestDataTransfer->getSkuOrFail()]
            );
        }

        $productConfiguratorRequestTransfer = $this->mapProductConfigurationInstanceTransferToProductConfiguratorRequestTransfer(
            $productConfigurationInstanceTransfer,
            $productConfiguratorRequestTransfer
        );

        return $this->productConfigurationClient->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);
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
            true
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
