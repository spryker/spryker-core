<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

interface FactFinderClientInterface
{

    /**
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getProductCsv($locale, $number = '');

    /**
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getCategoryCsv($locale, $number = '');

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function search();

    /**
     * Returns the stored quote
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

}
