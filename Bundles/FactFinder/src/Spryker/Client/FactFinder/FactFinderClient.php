<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Zed\Collector\CollectorConfig;

/**
 * @method \Spryker\Client\FactFinder\FactFinderFactory getFactory()
 */
class FactFinderClient extends AbstractClient implements FactFinderClientInterface
{

    /**
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getProductCsv($locale, $number = '')
    {
        return $this->getFactory()
            ->createZedFactFinderStub()
            ->getExportedCsv($locale, CollectorConfig::COLLECTOR_TYPE_PRODUCT_ABSTRACT, $number);
    }

    /**
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getCategoryCsv($locale, $number = '')
    {
        return $this->getFactory()
            ->createZedFactFinderStub()
            ->getExportedCsv($locale, CollectorConfig::COLLECTOR_TYPE_CATEGORYNODE, $number);
    }


    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function search()
    {
        $ffSearchResponseTransfer = $this->getFactory()
            ->createZedFactFinderStub()
            ->search($this->getQuote());

        return $ffSearchResponseTransfer;
    }

    /**
     * Returns the stored quote
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    protected function getSession()
    {
        return $this->getFactory()->createSession();
    }

}
