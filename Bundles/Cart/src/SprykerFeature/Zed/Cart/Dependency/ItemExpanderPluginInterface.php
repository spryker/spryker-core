<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Dependency;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;

interface ItemExpanderPluginInterface
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change);

}
