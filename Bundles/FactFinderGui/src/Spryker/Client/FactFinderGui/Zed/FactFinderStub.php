<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Zed;

use Generated\Shared\Transfer\FactFinderCsvTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class FactFinderStub implements FactFinderStubInterface
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param string $locale
     * @param string $type
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getExportedCsv($locale, $type, $number = '')
    {
        $factFinderTransfer = new FactFinderCsvTransfer();
        $factFinderTransfer
            ->setType($type)
            ->setLocale($locale)
            ->setNumber($number);

        return $this->zedStub->call('/fact-finder/gateway/get-fact-finder-csv', $factFinderTransfer);
    }

    //@todo @artem delete
//    /**
//     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
//     *
//     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
//     */
//    public function search(QuoteTransfer $quoteTransfer)
//    {
//        return $this->zedStub->call('/fact-finder/gateway/search', $quoteTransfer);
//    }

}
