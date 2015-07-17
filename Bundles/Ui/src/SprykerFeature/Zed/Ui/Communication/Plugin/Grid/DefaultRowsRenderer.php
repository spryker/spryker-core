<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Grid;

use SprykerFeature\Zed\Ui\Dependency\Plugin\AbstractGridPlugin;
use Propel\Runtime\Map\TableMap;

class DefaultRowsRenderer extends AbstractGridPlugin
{

    const DATA_DEFINITION_ROWS = 'rows';

    /**
     * @param array $data
     *
     * @return array
     */
    public function getData(array $data)
    {
        $collection = $this->getStateContainer()->getQueryResult();
        $data[self::DATA_DEFINITION_ROWS] = $collection->toArray(null, false, TableMap::TYPE_FIELDNAME);

        return $data;
    }

}
