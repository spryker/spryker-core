<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\WishlistBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Wishlist\Business\Integrator\EntityIntegrator;
use SprykerFeature\Zed\Wishlist\Business\Integrator\TransferObjectIntegrator;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItemQuery;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * @method WishlistBusiness getFactory()
 * @method WishlistQueryContainer getQueryContainer()
 */
class WishlistDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return EntityIntegrator
     */
    public function createEntityIntegrator()
    {
        return $this->getFactory()
            ->createIntegratorEntityIntegrator($this->getQueryContainer());
    }

    /**
     * @return TransferObjectIntegrator
     */
    public function createTransferObjectIntegrator()
    {
        return $this->getFactory()
            ->createIntegratorTransferObjectIntegrator($this->createEntityIntegrator());
    }

    public function createMergeransferObjectIntegrator()
    {
        return $this->getFactory()
            ->createIntegratorTransferObjectIntegrator($this->createEntityIntegrator(), TransferObjectIntegrator::MERGE_MODE);
    }

    /**
     * @return SpyWishlistItemQuery
     */
    public function createWishlistItemQuery()
    {
        return $this->getQueryContainer()->getWishlistItemQuery();
    }

}
