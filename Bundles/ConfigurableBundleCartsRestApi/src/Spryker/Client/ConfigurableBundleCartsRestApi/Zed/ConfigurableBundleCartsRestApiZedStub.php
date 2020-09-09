<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartsRestApi\Zed;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Client\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToZedRequestClientInterface;

class ConfigurableBundleCartsRestApiZedStub implements ConfigurableBundleCartsRestApiZedStubInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ConfigurableBundleCartsRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\ConfigurableBundleCartsRestApi\Communication\Controller\GatewayController::addConfiguredBundleAction()
     *
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundle(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/configurable-bundle-carts-rest-api/gateway/add-configured-bundle',
            $createConfiguredBundleRequestTransfer
        );

        return $quoteResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ConfigurableBundleCartsRestApi\Communication\Controller\GatewayController::updateConfiguredBundleQuantityAction()
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/configurable-bundle-carts-rest-api/gateway/update-configured-bundle-quantity',
            $updateConfiguredBundleRequestTransfer
        );

        return $quoteResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ConfigurableBundleCartsRestApi\Communication\Controller\GatewayController::removeConfiguredBundleAction()
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/configurable-bundle-carts-rest-api/gateway/remove-configured-bundle',
            $updateConfiguredBundleRequestTransfer
        );

        return $quoteResponseTransfer;
    }
}
