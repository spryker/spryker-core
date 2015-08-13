<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EditController extends AbstractController
{

    const PARAM_ID_CATEGORY = 'id-category';

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->get(self::PARAM_ID_CATEGORY);
        
        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryForm($idCategory)
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $categoryTransfer = (new CategoryTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                ->updateCategory($categoryTransfer, $idCategory)
            ;
            
            return $this->redirectResponse('/category/');
        }

        return $this->viewResponse([
            self::PARAM_ID_CATEGORY => $idCategory,
            'form' => $form->createView(),
        ]);
    }

}
