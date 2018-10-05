<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface;
use Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface;

abstract class AbstractTouchUpdater implements TouchUpdaterInterface
{
    public const FK_TOUCH = 'fk_touch';

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
     * @var \Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface
     */
    protected $bulkTouchUpdateQuery;

    /**
     * @var \Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface
     */
    protected $bulkTouchDeleteQuery;

    /**
     * @param string $key
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    abstract protected function findOrCreateTouchKeyEntity($key, $idLocale, $idStore);

    /**
     * @param string[] $keys
     * @param int $idLocale
     *
     * @return void
     */
    abstract public function deleteTouchKeyEntities($keys, $idLocale);

    /**
     * @param \Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface $bulkTouchUpdateQuery
     * @param \Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface $bulkTouchDeleteQuery
     */
    public function __construct(
        BulkUpdateTouchKeyByIdQueryInterface $bulkTouchUpdateQuery,
        BulkDeleteTouchByIdQueryInterface $bulkTouchDeleteQuery
    ) {
        $this->bulkTouchUpdateQuery = $bulkTouchUpdateQuery;
        $this->bulkTouchDeleteQuery = $bulkTouchDeleteQuery;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param int $idStore
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return void
     */
    public function bulkUpdate(TouchUpdaterSet $touchUpdaterSet, $idLocale, $idStore, ?ConnectionInterface $connection = null)
    {
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $idKey = $this->findCollectorKeyFromData($touchData);

            if ($idKey !== null) {
                $this->bulkTouchUpdateQuery->addQuery(
                    $this->touchKeyTableName,
                    $key,
                    $this->touchKeyIdColumnName,
                    $idKey
                );
            } else {
                /** @var \Orm\Zed\Touch\Persistence\SpyTouchStorage|\Orm\Zed\Touch\Persistence\SpyTouchSearch $entity */
                $entity = $this->findOrCreateTouchKeyEntity($key, $idLocale, $idStore);
                $entity->setFkTouch($touchData[CollectorConfig::COLLECTOR_TOUCH_ID]);
                $entity->save();
            }
        }

        $updateSql = $this->bulkTouchUpdateQuery->getRawSqlString();
        $this->bulkTouchUpdateQuery->flushQueries();
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
    public function bulkDelete(TouchUpdaterSet $touchUpdaterSet, $idLocale, ?ConnectionInterface $connection = null)
    {
        $idsToDelete = [];
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $idTouch = $touchData[CollectorConfig::COLLECTOR_TOUCH_ID];

            if ($idTouch !== null) {
                $idsToDelete[$idTouch] = $idTouch;
            }
        }

        if (!empty($idsToDelete) && $connection !== null) {
            $sql = $this->bulkTouchDeleteQuery
                ->addQuery(
                    $this->touchKeyTableName,
                    static::FK_TOUCH,
                    $idsToDelete
                )
                ->getRawSqlString();
            $this->bulkTouchDeleteQuery->flushQueries();
            $connection->exec($sql);
        }
    }

    /**
     * @param array $touchData
     *
     * @return int|null
     */
    protected function findCollectorKeyFromData(array $touchData)
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
