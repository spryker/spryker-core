<?php

namespace SprykerFeature\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\ProductCategory\Communication\Form\CategoryFormEdit;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;
use SprykerFeature\Zed\ProductCategory\Communication\ProductCategoryDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductCategoryFacade getFacade()
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class DeleteController extends EditController
{

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConfig::PARAM_ID_CATEGORY);

        if (!$this->existsCategory($idCategory)) {
            $this->addErrorMessage(sprintf('The category with id "%s" does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $form = $this->getDependencyContainer()
            ->createCategoryFormDelete($idCategory)
            ->handleRequest()
        ;

        if ($form->isValid()) {
            $locale = $this->getDependencyContainer()
                ->createCurrentLocale();

            $connection = Propel::getConnection();
            $connection->beginTransaction();

            $data = $form->getData();

            $currentCategoryTransfer = (new CategoryTransfer())
                ->fromArray($data, true);

            $sourceTransfer = (new NodeTransfer())
                ->fromArray($data, true);

            if ($data['delete_children']) {
                $this->getDependencyContainer()
                    ->createProductCategoryFacade()
                    ->deleteCategoryRecursive($currentCategoryTransfer->getIdCategory(), $locale);
            } else {
                if ($sourceTransfer->getFkParentCategoryNode() === 0) {
                    throw new \InvalidArgumentException('Please select a category');
                }

                if ($sourceTransfer->getIdCategoryNode() === $sourceTransfer->getFkParentCategoryNode()) {
                    throw new \InvalidArgumentException('Please select another category');
                }

                $sourceTransfer = $this->getDependencyContainer()
                    ->createCategoryFacade()
                    ->getNodeById($data['id_category_node']);

                $destinationEntity = $this->getDependencyContainer()
                    ->createCategoryFacade()
                    ->getNodeById($data['fk_parent_category_node']);

                $sourceNodeTransfer = (new NodeTransfer())
                    ->fromArray($sourceTransfer->toArray());

                $destinationNodeTransfer = (new NodeTransfer())
                    ->fromArray($destinationEntity->toArray());

                $this->getDependencyContainer()
                    ->createProductCategoryFacade()
                    ->moveCategoryChildrenAndDeleteNode($sourceNodeTransfer, $destinationNodeTransfer, $locale);

                $this->getDependencyContainer()
                    ->createProductCategoryFacade()
                    ->deleteCategoryRecursive($currentCategoryTransfer->getIdCategory(), $locale);
            }

            $this->addSuccessMessage('The category was deleted successfully.');

            $connection->commit();

            return $this->redirectResponse('/category');
        }

        return $this->viewResponse($this->getViewData($idCategory, $form));
    }

    /**
     * @param $idCategory
     *
     * @return bool
     */
    private function existsCategory($idCategory)
    {
        $categoryCount = $this->getDependencyContainer()
            ->createCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->count()
        ;

        if ($categoryCount === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $idCategory
     * @param CategoryFormEdit $form
     *
     * @return array
     */
    private function getViewData($idCategory, CategoryFormEdit $form)
    {
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $categoryEntity = $this->getDependencyContainer()
            ->createCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->findOne()
        ;

        $productCategoryTable = $this->getDependencyContainer()
            ->createProductCategoryTable($locale, $idCategory)
        ;

        $productTable = $this->getDependencyContainer()
            ->createProductTable($locale, $idCategory)
        ;

        return [
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $productCategoryTable->render(),
            'productsTable' => $productTable->render(),
            'showProducts' => false,
            'currentCategory' => $categoryEntity->toArray(),
            'paths' => $this->getPaths($categoryEntity, $locale),
            'products' => $this->getProducts($categoryEntity, $locale),
            'blocks' => $this->getBlocks($categoryEntity, $locale),
        ];
    }

}
