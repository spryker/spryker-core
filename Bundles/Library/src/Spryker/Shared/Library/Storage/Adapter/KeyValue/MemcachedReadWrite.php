<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\KeyValue;

use Exception;

class MemcachedReadWrite extends MemcachedRead implements ReadWriteInterface
{

    /**
     * @param string $key
     * @param mixed $value
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function set($key, $value)
    {
        $result = $this->getResource()->set($key, $value);
        $this->addWriteAccessStats($key);
        if (!$result) {
            throw new Exception(
                'could not set memcacheKey: "' . $key . '" with value: "' . json_encode($value) . '"'
            );
        }

        return $result;
    }

    /**
     * @param array $items
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function setMulti(array $items)
    {
        $result = $this->getResource()->setMulti($items);
        $this->addMultiWriteAccessStats($items);
        if (!$result) {
            throw new Exception(
                'could not set memcacheKeys for items: "[' . implode(',', array_keys($items)) . ']" with values: "[' . implode(',', array_values($items)) . ']"'
            );
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key)
    {
        $result = $this->getResource()->delete($key);
        $this->addDeleteAccessStats($key);

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getResource()->deleteMulti($keys);
        $this->addMultiDeleteAccessStats($keys);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        $deleteCount = 0;
        while ($keys = $this->getAllKeys()) {
            foreach ($keys as $key) {
                if ($this->getResource()->delete($key)) {
                    $deleteCount++;
                }
            }
        }

        return $deleteCount;
    }

}
