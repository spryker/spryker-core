<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;

interface ExporterInterface
{

    /**
     * @param string $type
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $locale);

}
