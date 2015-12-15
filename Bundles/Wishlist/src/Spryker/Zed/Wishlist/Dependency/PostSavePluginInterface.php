<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency;

interface PostSavePluginInterface
{

    /**
     * @param \ArrayObject||ItemInterface[] $items
     */
    public function trigger(\ArrayObject $items);

}
