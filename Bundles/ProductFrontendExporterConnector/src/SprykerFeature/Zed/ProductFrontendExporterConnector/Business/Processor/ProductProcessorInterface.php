<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductProcessorInterface
{

    /**
     * @param array $products
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildProducts(array $products, LocaleTransfer $locale);

}
