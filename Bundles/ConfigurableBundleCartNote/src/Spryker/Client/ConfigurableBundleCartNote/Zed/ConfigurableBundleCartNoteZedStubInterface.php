<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\Zed;

use Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartNoteZedStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer $configurableBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(
        ConfigurableBundleCartNoteRequestTransfer $configurableBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer;
}
