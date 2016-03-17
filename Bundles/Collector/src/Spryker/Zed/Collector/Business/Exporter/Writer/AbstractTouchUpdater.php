<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\CollectorConfig;

abstract class AbstractTouchUpdater implements TouchUpdaterInterface
{

    /**
     * @var string
     */
    protected $touchKeyTableName;

    /**
     * @var string
     */
    protected $touchKeyIdColumnName;

    /**
     * @var string
     */
    protected $touchKeyColumnName;

    /**
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    abstract protected function createTouchKeyEntity();

    /**
     * @param array $idsToDelete
     *
     * @return string
     */
    protected function getDeleteSql(array $idsToDelete)
    {
        $sql = implode(',', $idsToDelete);
        $sql = rtrim($sql, ',');

        return sprintf(
            "DELETE FROM %s WHERE %s IN (%s); \n",
            $this->touchKeyTableName,
            $this->touchKeyIdColumnName,
            $sql
        );
    }

    /**
     * @param string $idKey
     * @param string $key
     *
     * @return string
     */
    protected function getUpdateSql($idKey, $key)
    {
        return sprintf(
            "UPDATE %s SET key = '%s' WHERE %s = '%s'; \n",
            $this->touchKeyTableName,
            $key,
            $this->touchKeyIdColumnName,
            $idKey
        );
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null)
    {
        $updateSql = '';
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $idKey = $this->getCollectorKeyFromData($touchData);

            if ($idKey !== null) {
                $updateSql .= $this->getUpdateSql($idKey, $key);
            } else {
                /* @var SpyTouchStorage|SpyTouchSearch $entity */
                $entity = $this->createTouchKeyEntity();
                $entity->setKey($key);
                $entity->setFkTouch($touchData[CollectorConfig::COLLECTOR_TOUCH_ID]);
                $entity->setFkLocale($idLocale);
                $entity->save();
            }
        }

        if (trim($updateSql) !== '' && $connection !== null) {
            $connection->exec($updateSql);
        }
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return void
     */
    public function deleteMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null)
    {
        $idsToDelete = '';
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $idTouch = $touchData[CollectorConfig::COLLECTOR_TOUCH_ID];

            if ($idTouch !== null) {
                $idsToDelete[] = $idTouch;
            }
        }

        if (!empty($idsToDelete) && $connection !== null) {
            $sql = $this->getDeleteSql($idsToDelete);
            $connection->exec($sql);
        }
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

        if (!isset($data[$this->touchKeyColumnName])) {
            return null;
        }

        return $data[$this->touchKeyColumnName];
    }

    /**
     * @return string
     */
    public function getTouchKeyColumnName()
    {
        return $this->touchKeyColumnName;
    }

}
