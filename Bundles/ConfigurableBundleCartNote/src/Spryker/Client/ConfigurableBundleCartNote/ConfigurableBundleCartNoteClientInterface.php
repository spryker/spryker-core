<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartNoteClientInterface
{
    /**
     * Specification:
     * - Sets cart note to configurable bundle.
     *
     * @api
     *
     * @param string $note
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(string $note, string $configurableBundleGroupKey): QuoteResponseTransfer;
}
