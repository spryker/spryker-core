<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Zed;

use Generated\Shared\Transfer\QuoteTransfer;

interface FactFinderStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function search(QuoteTransfer $quoteTransfer);

}
