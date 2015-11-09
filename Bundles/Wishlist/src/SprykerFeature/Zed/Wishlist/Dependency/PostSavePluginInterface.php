<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Dependency;

interface PostSavePluginInterface
{

    /**
     * @param \ArrayObject||ItemInterface[] $items
     */
    public function trigger(\ArrayObject $items);

}
