<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Form\PoolCategoryForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method VoucherDependencyContainer getDependencyContainer()
 */
class PoolController extends AbstractController
{

    public function createCategoryAction(Request $request, $idPoolCategory = 0)
    {
        $form = $this->getDependencyContainer()->createPoolCategoryForm($idPoolCategory);
        $form->handleRequest();

        if ($form->isValid()) {
            $facade = $this->getFacade();

            $category = new VoucherPoolCategoryTransfer();
            $category->fromArray($form->getData());

            $facade->createDiscountVoucherPoolCategory($category);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    public function editCategoryAction(Request $request)
    {
        $idPoolCategory = $request->query->get('id', 0);

        return $this->createCategoryAction($request, $idPoolCategory);
    }

    public function indexAction()
    {
    }

    public function categoryAction()
    {
    }

    public function poolAction()
    {
    }

}
