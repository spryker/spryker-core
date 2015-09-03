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

        $productCategoryTable = $this->getDependencyContainer()
            ->createProductCategoryTable($locale, $idCategory)
        ;

        return $this->jsonResponse(
            $productCategoryTable->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function productTableAction(Request $request)
    {
        $idCategory = $request->get(CategoryConfig::PARAM_ID_CATEGORY);
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $productTable = $this->getDependencyContainer()
            ->createProductTable($locale, $idCategory)
        ;

        return $this->jsonResponse(
            $productTable->fetchData()
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
        $idCategory = $request->get(CategoryConfig::PARAM_ID_CATEGORY);
        
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $resultSet = $this->getDependencyContainer()
            ->createProductFacade()
            ->getAbstractProductsBySearchTerm($term, $locale, $idCategory);
        ;
        
        $results = [];
        foreach ($resultSet as $searchItem) {
            $item = $searchItem->toArray();
            //fix for select 2
            $item['id'] = $item['id_abstract_product'];
            $item['text'] = $item['name'].' ('.$item['sku'].')';
            $results[] = $item;
        }
        
        return $this->jsonResponse([
            'items' => $results,
            "incomplete_results" => false,
            'total_count' => count($results)
        ]);
    }

}
