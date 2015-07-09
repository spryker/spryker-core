<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

interface RowTypeDetectorInterface extends TypeDetectorInterface
{

    /**
     * @param mixed $row
     * @param Input $input
     *
     * @return mixed
     */
    public function detectByRow($row, Input $input);

}
