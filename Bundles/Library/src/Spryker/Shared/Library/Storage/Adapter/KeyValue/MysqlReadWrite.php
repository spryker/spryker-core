<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\KeyValue;

class MysqlReadWrite extends MysqlRead implements ReadWriteInterface
{

    /**
     * @param string $key
     * @param mixed $value
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
     * @param string $key
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
     * @return int
     */
    public function deleteAll()
    {
        return $this->runDeleteAll();
    }

    /**
     * @param string $key
     * @param mixed $value
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
     * @param string $key
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
