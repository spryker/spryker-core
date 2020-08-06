<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleNote\Zed;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleNote\Dependency\Client\ConfigurableBundleNoteToZedRequestClientInterface;

class ConfigurableBundleNoteZedStub implements ConfigurableBundleNoteZedStubInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleNote\Dependency\Client\ConfigurableBundleNoteToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleNote\Dependency\Client\ConfigurableBundleNoteToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ConfigurableBundleNoteToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\ConfigurableBundleNote\Communication\Controller\GatewayController::setConfiguredBundleNoteAction()
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/configurable-bundle-note/gateway/set-configured-bundle-note',
            $configuredBundleNoteRequestTransfer
        );

        return $quoteResponseTransfer;
    }
}
