<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleNote;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleNoteClientInterface
{
    /**
     * Specification:
     * - Resolves quote storage strategy which implements `\Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy\QuoteStorageStrategyInterface`.
     * - Updates configured bundle with note using quote storage strategy.
     * - Returns `isSuccess=true` if note was successfully set or `isSuccess=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @throws \Spryker\Client\ConfigurableBundleNote\Exception\QuoteStorageStrategyNotFound
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer;
}
