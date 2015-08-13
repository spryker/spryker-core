<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Communication\Table\CategoryAttributeTable;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddController extends AbstractController
{

    const PARAM_ID_CATEGORY = 'id-category';

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');
        $form = $this->getDependencyContainer()
            ->createCategoryForm(1)
        ;
        $form->handleRequest();

        if ($form->isValid()) {

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }

}
