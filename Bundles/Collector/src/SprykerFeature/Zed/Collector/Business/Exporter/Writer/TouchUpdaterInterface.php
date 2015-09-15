<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer;

use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;

interface TouchUpdaterInterface
{
    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param $idLocale
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale);

}
