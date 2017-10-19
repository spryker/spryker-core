<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business\Model\Formatter;

use Propel\Runtime\Formatter\SimpleArrayFormatter;

class AssociativeArrayFormatter extends SimpleArrayFormatter
{
    /**
     * @param array $row
     *
     * @return array
     */
    public function getStructuredArrayFromRow($row)
    {
        $columnNames = array_keys($this->getAsColumns());

        $finalRowFallback = [];
        $finalRow = [];
        foreach ($row as $index => $value) {
            $key = str_replace('"', '', $columnNames[$index]);
            $finalRowFallback[$key] = $value;

            $key = $this->getKeyName($key);
            $finalRow[$key] = $value;
        }

        if (count($finalRow) !== count($finalRowFallback)) {
            return $finalRowFallback;
        }

        return $finalRow;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getKeyName($key)
    {
        if ($this->isPhpName($key)) {
            if (strpos($key, '.') === false) {
                return $key;
            }

            $phpName = substr($key, strpos($key, '.') + 1);
            $phpName = lcfirst($phpName);

            $separator = '_';
            $key = strtolower(preg_replace(
                '/([a-z])([A-Z])/',
                '$1' . addcslashes($separator, '$') . '$2',
                $phpName
            ));
        }

        return $key;
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    protected function isPhpName($keyName)
    {
        return strpos($keyName, '\\') !== false;
    }
}
