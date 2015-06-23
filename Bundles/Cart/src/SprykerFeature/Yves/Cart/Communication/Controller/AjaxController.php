<?php

namespace SprykerFeature\Yves\Cart\Communication\Controller;

use SprykerEngine\Yves\Application\Communication\Controller\AbstractController;
use SprykerFeature\Yves\Cart\CartDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
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
        $cartClient = $this->getDependencyContainer()->createCartClient();
        $cartClient->addItem($sku, $quantity);

        return $this->jsonResponse([
            'count' => $cartClient->getItemCount(),
        ]);
    }

}
