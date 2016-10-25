<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;

interface FactFinderClientInterface
{

    /**
     * @api
     *
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getProductCsv($locale, $number = '');

    /**
     * @api
     *
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getCategoryCsv($locale, $number = '');

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function search(FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer);

}
