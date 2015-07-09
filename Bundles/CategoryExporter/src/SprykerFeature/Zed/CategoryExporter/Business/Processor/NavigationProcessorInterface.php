<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

interface NavigationProcessorInterface
{

    /**
     * @param array $categoryNodes
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function process(array $categoryNodes, LocaleTransfer $locale);

}
