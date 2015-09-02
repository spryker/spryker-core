<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{

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

            $this->addSuccessMessage('The category was added successfully.');

            return $this->redirectResponse('/category');
        }

        return $this->viewResponse([
            'form' => $form->createView()
        ]);
    }



    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function productCategoryTableAction(Request $request)
    {
        $idCategory = $request->get(CategoryConfig::PARAM_ID_CATEGORY);
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $table = $this->getDependencyContainer()
            ->createProductCategoryTable($locale, $idCategory)
        ;

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function productCategorySuggestProductAction(Request $request)
    {
        $term = $request->get(CategoryConfig::PARAM_PRODUCT_PHRASE_SUGGEST);
        
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $resultSet = $this->getDependencyContainer()
            ->createProductFacade()
            ->getAbstractProductsBySearchTerm($term, $locale);
        ;
        
        $results = [];
        foreach ($resultSet as $searchItem) {
            $results[$searchItem->getIdAbstractProduct()] = $searchItem->toArray();
        }
        
        return $this->jsonResponse(
            $results
        );
    }

}
