<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class MysqlRead extends Mysql implements ReadInterface
{

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $value = $this->runGet($key);
        $this->addReadAccessStats($key);

        return $value;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        $values = $this->runGetMulti($keys);
        $this->addMultiReadAccessStats($keys);

        return $values;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        //        $stats = [];
////        $result = $this->runQuery('SHOW GLOBAL STATUS');
//
//        $statement = $this->resource->query('SHOW GLOBAL STATUS');
//        $statement->execute();
//
//        while ($row = $statement->fetch()) {
//            $stats[$row[self::FIELD_STATS_VARIABLE_NAME]] = $row[self::FIELD_STATS_VALUE];
//        }
//
//        return $stats;
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    public function getAllKeys()
    {
        //        $keys = [];
//        $query = sprintf(
//            'SELECT `%s` FROM `%s`',
//            $this->getResource()->escape_string(self::FIELD_KEY),
//            $this->getResource()->escape_string(self::TABLE_NAME)
//        );
//        $result = $this->runQuery($query);
//        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
//            $keys[] = $row[self::FIELD_KEY];
//        }
//
//        return $keys;
    }

    /**
     * @param $key
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function runGet($key)
    {
        $value = null;
        $tableName = $this->getTableName();
        $query = 'SELECT ' . self::FIELD_VALUE . ' FROM ' . $tableName . ' WHERE ' . $tableName . '.' . self::FIELD_KEY . ' = ?';

        $resource = $this->getResource();
        $statement = $resource->prepare($query);
        $statement->execute([$key]);

        $result = $statement->fetch();

        if ($result) {
            $value = json_decode($result[self::FIELD_VALUE], true);
        }

        return $value;
    }

    /**
     * @param array $keys
     *
     * @throws \Exception
     */
    protected function runGetMulti(array $keys)
    {
        //        if (empty($keys)) {
//            return [];
//        }
//
//        $valueMulti = array_fill_keys($keys, null);
//        $keysQuery = [];
//        foreach ($keys as $key) {
//            $keysQuery[]= sprintf(
//                '`%s` = \'%s\'',
//                $this->getResource()->escape_string(self::FIELD_KEY),
//                $this->getResource()->real_escape_string($key)
//            );
//        }
//
//        $query = sprintf(
//            'SELECT * FROM `%s` WHERE (%s)',
//            $this->getResource()->escape_string(self::TABLE_NAME),
//            implode(' OR ', $keysQuery)
//        );
//
//        $tmpValueMulti = [];
//        $result = $this->runQuery($query);
//        while (($row = $result->fetch_assoc()) !== null) {
//            $tmpValueMulti[$row[self::FIELD_KEY]] = unserialize($row[self::FIELD_VALUE]);
//        }
//
//        return array_merge($valueMulti, $tmpValueMulti);
    }

    /**
     * @return int
     */
    public function getCountItems()
    {
        $query = 'SELECT count(*) as items FROM ' . $this->getTableName();
        $statement = $this->getResource()->query($query);
        $statement->execute();
        $result = $statement->fetch();

        return (int) $result['items'];
    }

}
