<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

interface OrderSequenceInterface
{

    /**
     * @return string
     */
    public function generate();

}
