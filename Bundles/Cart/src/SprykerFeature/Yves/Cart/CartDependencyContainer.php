<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart;

use Generated\Yves\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Cart\Service\CartClientInterface;

/**
 * @method Cart getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return CartClientInterface
     */
    public function createCartClient()
    {
        return $this->getLocator()->cart()->client();
    }

}
