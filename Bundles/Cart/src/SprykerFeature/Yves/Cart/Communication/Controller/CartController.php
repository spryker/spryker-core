<?php

namespace SprykerFeature\Yves\Cart\Communication\Controller;

use SprykerEngine\Yves\Application\Communication\Controller\AbstractController;
use SprykerFeature\Yves\Cart\CartDependencyContainer;
use SprykerFeature\Yves\Cart\Communication\Plugin\CartControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $cartClient = $this->getDependencyContainer()->createCartClient();
        $cartItems = $cartClient->getCart()->getItems();

        return $this->viewResponse([
            'cartItems' => $cartItems,
            'totals' => $cartClient->getCart()->getTotals(),
        ]);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return RedirectResponse
     */
    public function addAction($sku, $quantity)
    {
        $cartClient = $this->getDependencyContainer()->createCartClient();
        $cartClient->addItem($sku, $quantity);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     *
     * @return RedirectResponse
     */
    public function removeAction($sku)
    {
        $cartClient = $this->getDependencyContainer()->createCartClient();
        $cartClient->removeItem($sku);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return RedirectResponse
     */
    public function changeAction($sku, $quantity)
    {
        $cartClient = $this->getDependencyContainer()->createCartClient();
        $cartClient->changeItemQuantity($sku, $quantity);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

}
