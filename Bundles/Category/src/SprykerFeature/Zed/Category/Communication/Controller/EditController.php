<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryQueryContainer getQueryContainer()
 */
class EditController extends AbstractController
{

    const PARAM_ID_CATEGORY = 'id-category';

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->get(self::PARAM_ID_CATEGORY);

        $categoryExists = $this->getQueryContainer()->queryCategoryById($idCategory)->findOne();
        if (!$categoryExists) {
            $this->getDependencyContainer()->getFlashMessengerFacade()->addErrorMessage('The category you are trying to edit does not exist.');
            return new RedirectResponse('/category/');
        }

        /**
         * @var Form $form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryFormEdit($idCategory)
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $locale = $this->getDependencyContainer()
                ->createCurrentLocale()
            ;

            $categoryTransfer = (new CategoryTransfer())
                ->fromArray($form->getData(), true)
            ;
            
            $this->getFacade()
                ->updateCategory($categoryTransfer, $locale)
            ;

            $categoryNodeTransfer = (new NodeTransfer())
                ->fromArray($form->getData(), true)
            ;

            $this->getFacade()
                ->updateNodeWithTreeWriter($categoryNodeTransfer)
            ;

            $this->getDependencyContainer()->getFlashMessengerFacade()->addSuccessMessage('The category was saved successfully.');
            
            return $this->redirectResponse('/category/');
        }

        return $this->viewResponse([
            'idCategory' => $idCategory,
            'form' => $form->createView()
        ]);
    }

}
