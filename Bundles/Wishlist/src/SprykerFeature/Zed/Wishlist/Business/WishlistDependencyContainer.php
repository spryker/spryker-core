<?php

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Wishlist\WishlistItemInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\WishlistBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * @method WishlistBusiness getFactory()
 * @method WishlistQueryContainer getQueryContainer()
 */
class WishlistDependencyContainer extends AbstractBusinessDependencyContainer
{
    public function getEntityIntegrator()
    {
        return $this->getFactory()
            ->createIntegratorEntityIntegrator($this->getQueryContainer());
    }

    public function getTransferObjectIntegrator()
    {
        return $this->getFactory()
            ->createIntegratorTransferObjectIntegrator($this->getEntityIntegrator());
    }

    public function getWishlistItemQuery()
    {
        return $this->getQueryContainer()->getWishlistItemQuery();
    }

}
