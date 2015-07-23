<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business;

use Generated\Shared\ItemGrouper\GroupableContainerInterface;

interface GroupableInterface
{
    /**
     * @return GroupableContainerInterface
     */
    public function getGroupableObject();
}
