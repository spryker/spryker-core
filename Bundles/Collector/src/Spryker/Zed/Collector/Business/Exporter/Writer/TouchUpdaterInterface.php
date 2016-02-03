<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;

interface TouchUpdaterInterface
{

    const TOUCH_EXPORTER_ID = 'exporter_touch_id';

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale);

    /**
     * @param int $idTouch
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchSearch
     */
    public function getKeyById($idTouch, LocaleTransfer $locale);

}
