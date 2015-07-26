<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Client\Ide\FactoryAutoCompletion\WishlistService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Wishlist\Service\Action\GetAction;
use SprykerFeature\Client\Wishlist\Service\Action\MergeAction;
use SprykerFeature\Client\Wishlist\Service\Action\RemoveAction;
use SprykerFeature\Client\Wishlist\Service\Action\SaveAction;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method WishlistService getFactory()
 */
class WishlistDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @throws \ErrorException
     * @return SaveAction
     */
    public function createSaveAction()
    {

       return $this->getFactory()
            ->createActionSaveAction(
                $this->getProvidedDependency(WishlistDependencyProvider::SESSION),
                $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED),
                $this->getProvidedDependency(WishlistDependencyProvider::CUSTOMER_CLIENT)->getCustomer()
                );
    }

    /**
     * @throws \ErrorException
     * @return RemoveAction
     */
    public function createRemoveAction()
    {
        return $this->getFactory()
            ->createActionRemoveAction(
                $this->getProvidedDependency(WishlistDependencyProvider::SESSION),
                $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED),
                $this->getProvidedDependency(WishlistDependencyProvider::CUSTOMER_CLIENT)->getCustomer()
                );
    }

    /**
     * @throws \ErrorException
     * @return GetAction
     */
    public function createGetAction()
    {
        return $this->getFactory()
            ->createActionGetAction(
                $this->getProvidedDependency(WishlistDependencyProvider::SESSION),
                $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED),
                $this->getProvidedDependency(WishlistDependencyProvider::CUSTOMER_CLIENT)->getCustomer()
                );
    }

    /**
     * @throws \ErrorException
     * @return MergeAction
     */
    public function createMergeAction()
    {
        return $this->getFactory()
            ->createActionMergeAction(
                $this->getProvidedDependency(WishlistDependencyProvider::SESSION,
                $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED),
                $this->getProvidedDependency(WishlistDependencyProvider::CUSTOMER_CLIENT)->getCustomer()
                ));
    }

}
