<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryNodeProcessorInterface
{

    /**
     * @param array $categoryNodes
     * @param LocaleTransfer $locale
     *
     * @return mixed
     */
    public function process(array $categoryNodes, LocaleTransfer $locale);

}
