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
     * @param string $locale
     * @param string $type
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getExportedCsv($locale, $type, $number = '');

//    /**
//     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
//     *
//     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
//     */
//    public function search(QuoteTransfer $quoteTransfer);

}
