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
     * @param $row
     *
     * @return array
     */
    public function getStructuredArrayFromRow($row)
    {
        $columnNames = array_keys($this->getAsColumns());
        $finalRow = [];
        foreach ($row as $index => $value) {
            $finalRow[str_replace('"', '', $columnNames[$index])] = $value;
        }

        return $finalRow;
    }

}
