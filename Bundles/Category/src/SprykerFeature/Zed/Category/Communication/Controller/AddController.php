<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryDependencyContainer getDependencyContainer()
 */
class AddController extends AbstractController
{

    const PARAM_ID_CATEGORY = 'id-category';

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        /**
         * @var Form $form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryFormAdd()
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $locale = $this->getDependencyContainer()
                ->createCurrentLocale()
            ;

            $categoryTransfer = (new CategoryTransfer())
                ->fromArray($form->getData(), true)
            ;

            $idCategory = $this->getFacade()
                ->createCategory($categoryTransfer, $locale)
            ;

            $categoryNodeTransfer = (new NodeTransfer())
                ->fromArray($form->getData(), true)
            ;

            $categoryNodeTransfer->setFkCategory($idCategory);

            $this->getFacade()
                ->createCategoryNode($categoryNodeTransfer, $locale)
            ;

            return $this->redirectResponse('/category/');
        }

        return $this->viewResponse([
            'form' => $form->createView()
        ]);
    }

}
