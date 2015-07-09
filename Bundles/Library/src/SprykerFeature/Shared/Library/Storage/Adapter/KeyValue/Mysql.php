<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

/**
 * @property \PDO $resource
 *
 * @method \PDO getResource()
 */
abstract class Mysql extends AbstractKeyValue
{

    const TABLE_NAME = 'shared_data';
    const FIELD_KEY = 'key';
    const FIELD_VALUE = 'value';

    const FIELD_STATS_VARIABLE_NAME = 'Variable_name';
    const FIELD_STATS_VALUE = 'Value';

    /**
     * @return mixed|void
     */
    public function connect()
    {
        if (!$this->resource) {
            $host = $this->config['host'] ? $this->config['host'] : null;
            $database = $this->config['database'] ? $this->config['database'] : null;
            $port = $this->config['port'] ? $this->config['port'] : null;

            $resource = new \PDO(
                $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database,
                $this->config['user'] ? $this->config['user'] : null,
                $this->config['password'] ? $this->config['password'] : null
            );

            $this->resource = $resource;
            $this->initDb();
        }
    }

    /**
     * @throws \RuntimeException
     *
     * @return bool|\mysqli_result
     */
    protected function initDb()
    {
        if (empty($this->config['database'])) {
            throw new \RuntimeException('Database is not defined in config');
        }

        $query = 'CREATE TABLE IF NOT EXISTS  ' . $this->getTableName() . " (
  `key` varchar(255) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $statement = $this->getResource()->query($query);

        return $statement->execute();
    }

//    /**
//     * @param $query
//     * @return bool|\mysqli_result
//     * @throws \Exception
//     */
//    protected function runQuery($query)
//    {
//        $statement = $this->getResource()->query($query);
//        if (!$result) {
//            throw new \Exception($this->getResource()->error);
//        }
//        return $result;
//    }

    /**
     * @return string
     */
    protected function getTableName()
    {
        return $this->config['table'];
    }

    public function __destruct()
    {
        if ($this->resource) {
            unset($this->resource);
        }
    }

}
