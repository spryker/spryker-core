<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartNoteClientInterface
{
    /**
     * Specification:
     * - Resolves quote storage strategy which implements `\Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface`.
     * - Updates configured bundle with cart note using quote storage strategy.
     * - Returns `isSuccess=true` if cart note was successfully set or `isSuccess=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @throws \Spryker\Client\ConfigurableBundleCartNote\Exception\QuoteStorageStrategyNotFound
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleCartNote(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer;
}
