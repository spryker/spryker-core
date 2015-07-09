<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

interface FilterableProcessInterface
{

    /**
     * @return callable
     */
    public function getFilter();

}
