<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\PriceCartConnector\ChangeInterface;

interface PriceManagerInterface
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function addGrossPriceToItems(ChangeInterface $change);

}
