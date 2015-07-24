<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Table;

interface TableOptionsInterface
{
    /**
     * @param array $classesArray
     */
    public function addClass(array $classesArray);

    /**
     * @return array
     */
    public function getTableClass();

    /**
     * @return array
     */
    public function toArray();
}
