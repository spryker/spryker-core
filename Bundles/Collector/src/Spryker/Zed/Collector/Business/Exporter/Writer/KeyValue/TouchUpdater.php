<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Base\SpyTouchStorageQuery;
use Orm\Zed\Touch\Persistence\Map\SpyTouchStorageTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchStorage;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\CollectorConfig;

class TouchUpdater implements TouchUpdaterInterface
{

    const COLLECTOR_KEY_ID = CollectorConfig::COLLECTOR_STORAGE_KEY_ID;

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param ConnectionInterface $connection
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @internal param $idKey
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null)
    {
        $updateSql = '';
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $idKey = $this->getCollectorKeyFromData($touchData);

            if ($idKey !== null) {
                $sql = sprintf("UPDATE %s SET key = '%s' WHERE id_touch_storage = '%d'; \n",
                    SpyTouchStorageTableMap::TABLE_NAME,
                    $key,
                    $idKey
                );

                $updateSql .= $sql;
            } else {
                $entity = new SpyTouchStorage();
                $entity->setKey($key);
                $entity->setFkTouch($touchData[CollectorConfig::COLLECTOR_TOUCH_ID]);
                $entity->setFkLocale($idLocale);
                $entity->save();
            }
        }

        if (trim($updateSql) !== '' &&  $connection !== null) {
            $connection->exec($updateSql);
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

    /**
     * @param array $touchData
     *
     * @return int
     */
    protected function getCollectorKeyFromData(array $touchData)
    {
        if (!isset($touchData['data'])) {
            return null;
        }

        $data = $touchData['data'];
        if ($data === null) {
            return null;
        }

        return (isset($data[static::COLLECTOR_KEY_ID])) ? $data[static::COLLECTOR_KEY_ID] : null;
    }

}
