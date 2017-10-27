<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterController extends AbstractController
{
    const PARAM_ID_CATEGORY_NODE = 'id-category-node';

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->query->get(self::PARAM_ID_CATEGORY_NODE));
        $localeTransfer = $this->getCurrentLocale();

        $mainCategory = $this->getQueryContainer()
            ->queryCategoryByIdAndLocale($idCategory, $localeTransfer->getIdLocale())
            ->findOne();

        $productCategoryFilterDataProvider = $this->getFactory()
            ->createProductCategoryFilterDataProvider();

        $productCategoryFilterForm = $this->getFactory()
            ->createProductCategoryFilterForm(
                $productCategoryFilterDataProvider->getData(),
                $productCategoryFilterDataProvider->getOptions()
            )
            ->handleRequest($request);

//        if ($productCategoryFilterForm->isValid()) {
            /** @var \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer */
//            $productCategoryFilterTransfer = $productCategoryFilterForm->getData();

//            $productCategoryFilterTransfer->setFkNavigation($idNavigation);
//            if ($idNavigationNode) {
//                $productCategoryFilterTransfer->setFkParentNavigationNode($idNavigationNode);
//            }
//
//            $productCategoryFilterTransfer = $this->getFactory()
//                ->getNavigationFacade()
//                ->createNavigationNode($productCategoryFilterTransfer);
//
//            $this->addSuccessMessage(sprintf(
//                'Navigation node "%s" was created successfully.',
//                $productCategoryFilterTransfer->getNavigationNodeLocalizedAttributes()->getArrayCopy()[0]->getTitle()
//            ));
//
//            $queryParams = [
//                static::PARAM_ID_NAVIGATION => $idNavigation,
//                static::PARAM_ID_NAVIGATION_NODE => $idNavigationNode,
//                static::PARAM_ID_SELECTED_TREE_NODE => $idNavigationNode,
//            ];
//
//            if ($idNavigationNode) {
//                return $this->redirectResponse(Url::generate('/navigation-gui/node/update', $queryParams)->build());
//            } else {
//                return $this->redirectResponse(Url::generate('/navigation-gui/node/create', $queryParams)->build());
//            }
//        }

        return $this->viewResponse([
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
            'mainCategory' => $mainCategory,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
    }
}
