<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\Category\Business\CategoryFacade getFacade()
 * @method \SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer getDependencyContainer()
 */
class AddController extends AbstractController
{

    const PARAM_ID_CATEGORY = 'id-category';


    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryForm(null)
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $locale = $this->getDependencyContainer()->createCurrentLocale();
            $categoryTransfer = (new CategoryTransfer())->fromArray($form->getData(), true);
            $idCategory = $this->getFacade()
                ->createCategory($categoryTransfer, $locale)
            ;

            $categoryNodeTransfer = (new NodeTransfer())->fromArray($form->getData(), true);
            $categoryNodeTransfer->setFkCategory($idCategory);
            
            $this->getFacade()
                ->createCategoryNode($categoryNodeTransfer, $locale)
            ;

            return $this->redirectResponse('/category/');
        }

        return $this->viewResponse([
            self::PARAM_ID_CATEGORY => null,
            'form' => $form->createView(),
        ]);
    }

}
