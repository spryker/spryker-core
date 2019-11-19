<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Business;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartNoteFacadeInterface
{
    /**
     * Specification:
     * - Retrieves Quote from database by idQuote.
     * - Updates configured bundle with cart note.
     * - Updates Quote.
     * - Returns `isSuccess=true` if cart note was successfully set and quote was updated or `isSuccess=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfiguredBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer;
}
