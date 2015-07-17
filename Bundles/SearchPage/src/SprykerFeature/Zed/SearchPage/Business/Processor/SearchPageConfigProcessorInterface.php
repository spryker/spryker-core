<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

interface SearchPageConfigProcessorInterface
{

    /**
     * @param array $configRaw
     * @param LocaleTransfer $localeDto
     *
     * @return array
     */
    public function processSearchPageConfig(array $configRaw, LocaleTransfer $localeDto);

}
