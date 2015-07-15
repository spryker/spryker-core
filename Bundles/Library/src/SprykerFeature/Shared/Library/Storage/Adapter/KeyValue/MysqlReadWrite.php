<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class MysqlReadWrite extends MysqlRead implements ReadWriteInterface
{

    /**
     * @param $key
     * @param $value
     *
     * @throws \Exception
     *
     * @return mixed|void
     */
    public function set($key, $value)
    {
        $result = $this->runInsert($key, $value);
        $this->addWriteAccessStats($key);

        return $result;

    }

    /**
     * @param array $items
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function setMulti(array $items)
    {
        $result = [];
        foreach ($items as $key => $value) {
            $result[] = $this->runInsert($key, $value);
        }
        $this->addMultiWriteAccessStats($items);

        return $result;
    }

    /**
     * @param $key
     *
     * @throws \Exception
     *
     * @return bool|mixed|\mysqli_result
     */
    public function delete($key)
    {
        $result = $this->runDelete($key);
        $this->addDeleteAccessStats($key);

        return $result;
    }

    /**
     * @param array $keys
     *
     * @throws \Exception
     *
     * @return array
     */
    public function deleteMulti(array $keys)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->runDelete($key);
        }
        $this->addMultiDeleteAccessStats($keys);

        return $result;
    }

    /**
     * @throws \Exception
     *
     * @return int
     */
    public function deleteAll()
    {
        return $this->runDeleteAll();
    }

    /**
     * @param $key
     * @param $value
     *
     * @throws \Exception
     *
     * @return bool|\mysqli_result
     */
    protected function runInsert($key, $value)
    {
        $query = sprintf(
            'REPLACE INTO `%s` (`%s`,`%s`) VALUES (?, ?)',
            $this->getTableName(),
            self::FIELD_KEY,
            self::FIELD_VALUE
        );

        $statement = $this->getResource()->prepare($query);

        return $statement->execute([
            $key,
            json_encode($value),
        ]);
    }

    /**
     * @param $key
     *
     * @throws \Exception
     *
     * @return bool|\mysqli_result
     */
    protected function runDelete($key)
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `%s` = ?',
            $this->getTableName(),
            self::FIELD_KEY
        );

        $statement = $this->getResource()->prepare($query);

        return $statement->execute([$key]);
    }

    /**
     * @return int
     */
    protected function runDeleteAll()
    {
        $query = sprintf(
            'DELETE FROM `%s`',
            $this->getTableName()
        );

        $statement = $this->getResource()->query($query);
        $statement->execute();

        return $statement->rowCount();
    }

}
