<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\Zed;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface;

class ConfigurableBundleCartNoteZedStub implements ConfigurableBundleCartNoteZedStubInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ConfigurableBundleCartNoteToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\ConfigurableBundleCartNote\Communication\Controller\GatewayController::setConfiguredBundleCartNoteAction()
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleCartNote(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/configurable-bundle-cart-note/gateway/set-configured-bundle-cart-note',
            $configuredBundleCartNoteRequestTransfer
        );

        return $quoteResponseTransfer;
    }
}
