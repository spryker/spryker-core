<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle\Zed;

use Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface;

class ConfigurableBundleStub implements ConfigurableBundleStubInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ConfigurableBundleToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer
     */
    public function getConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): ConfiguredBundleCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer $configuredBundleCollectionTransfer */
        $configuredBundleCollectionTransfer = $this->zedRequestClient->call(
            '/configurable-bundle/gateway/get-configurable-bundles-from-quote',
            $quoteTransfer
        );

        return $configuredBundleCollectionTransfer;
    }
}
