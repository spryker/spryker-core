<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business\Manager;

use Generated\Shared\Cart\ChangeInterface;

interface ProductOptionManagerInterface
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandProductOptions(ChangeInterface $change);
}
