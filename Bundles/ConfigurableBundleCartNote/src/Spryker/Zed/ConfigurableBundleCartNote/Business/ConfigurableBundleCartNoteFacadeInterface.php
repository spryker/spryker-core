<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Business;

use Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartNoteFacadeInterface
{
    /**
     * Specification:
     * - Sets cart note to configurable bundle.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer $configurableBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(
        ConfigurableBundleCartNoteRequestTransfer $configurableBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer;
}
