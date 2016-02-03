<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency;

interface PostSavePluginInterface
{

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     */
    public function trigger(\ArrayObject $items);

}
