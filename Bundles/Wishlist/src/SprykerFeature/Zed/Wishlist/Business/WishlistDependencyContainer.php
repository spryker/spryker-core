<?php

namespace SprykerFeature\Zed\Wishlist\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

class WishlistDependencyContainer extends AbstractBusinessDependencyContainer
{
    public function getEntityManager()
    {
        return $this->getFactory()
                    ->createEntityManager($this->getQueryContainer());
    }

    public function getTransferObjectManager()
    {
        return $this->getFactory()
                    ->createTransferObjectManager($this->getEntityManager());
    }

    public function getWishlistItemQuery()
    {
        return $this->getQueryContainer()->getWishlistItemQuery();
    }
}
