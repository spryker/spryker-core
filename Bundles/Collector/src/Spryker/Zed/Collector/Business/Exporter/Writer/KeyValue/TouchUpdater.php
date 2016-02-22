<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Base\SpyTouchStorageQuery;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;

class TouchUpdater implements TouchUpdaterInterface
{

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     *
     * @return void
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale)
    {
        //TODO: make one raw query for whole set
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $query = SpyTouchStorageQuery::create();
            $query->filterByFkTouch($touchData[self::TOUCH_EXPORTER_ID]);
            $query->filterByFkLocale($idLocale);
            $entity = $query->findOneOrCreate();
            $entity->setKey($key);
            $entity->save();
        }
    }

    /**
     * @param int $idTouch
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchStorage
     */
    public function getKeyById($idTouch, LocaleTransfer $locale)
    {
        $query = SpyTouchStorageQuery::create();
        $query->filterByFkTouch($idTouch);
        $query->filterByFkLocale($locale->getIdLocale());

        return $query->findOne();
    }

}
