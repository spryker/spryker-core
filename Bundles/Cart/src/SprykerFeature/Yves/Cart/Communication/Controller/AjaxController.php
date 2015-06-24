<?php

namespace SprykerFeature\Yves\Cart\Communication\Controller;

use SprykerFeature\Yves\Cart\Communication\Plugin\CartControllerProvider;
use SprykerEngine\Yves\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AjaxController extends AbstractController
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return JsonResponse
     */
    public function addAction($sku, $quantity)
    {
        $cartClient = $this->getLocator()->cart()->client();
        $cartClient->addItem($sku, $quantity);

        return $this->jsonResponse([
            'count' => $cartClient->getItemCount(),
        ]);
    }

    /**
     * @param string $sku
     *
     * @return RedirectResponse
     */
    public function removeAction($sku)
    {
        $cartClient = $this->getLocator()->cart()->client();
        $cartClient->removeItem($sku);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART_OVERLAY);
    }

    /**
     * @param string $sku
     *
     * @return RedirectResponse
     */
    public function increaseAction($sku)
    {
        $cartClient = $this->getLocator()->cart()->client();
        $cartClient->increaseItemQuantity($sku);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART_OVERLAY);
    }

    /**
     * @param string $sku
     *
     * @return RedirectResponse
     */
    public function decreaseAction($sku)
    {
        $cartClient = $this->getLocator()->cart()->client();
        $cartClient->decreaseItemQuantity($sku);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART_OVERLAY);
    }




    /**
     * @param \ArrayObject $cartItems
     * @return array
     */
//    public function getProductsForCartItems(\ArrayObject $cartItems)
//    {
//        if (count($cartItems === 0)) {
//            return [];
//        }
//
//        $products = [];
//        /** @var CartItemTransfer $item */
//        foreach ($cartItems as $item) {
//            $product = [
//                'name' => '',
//                'price' => 0,
//                'quantity' => $item->getQuantity(),
//            ];
//
//            $abstractProduct = $this->getLocator()->catalog()->sdk()->createCatalogModel()->getProductDataById($item->getId());
//            if (isset($abstractProduct['abstract_Attributes']) && isset($abstractProduct['abstract_attributes']['thumbnail_url'])) {
//                $product['thumbnail'] = $abstractProduct['abstract_attributes']['thumbnail_url'];
//            }
//
//            if (isset($abstractProduct['concrete_products'])) {
//                foreach ($abstractProduct['concrete_products'] as $concreteProduct) {
//                    if (isset($concreteProduct['sku']) && $concreteProduct['sku'] == $item->getSku()) {
//                        if (isset($concreteProduct['name'])) {
//                            //@todo fall back to abstract name?
//                            $product['name'] = $concreteProduct['name'];
//                        }
//                    }
//                }
//            }
//
//            //@todo price from item?
//            if (isset($abstractProduct['valid_price'])) {
//                $product['price'] =  $abstractProduct['valid_price'];
//            }
//
//            $products[] = $product;
//        }
//
//        return $products;
//    }

}
