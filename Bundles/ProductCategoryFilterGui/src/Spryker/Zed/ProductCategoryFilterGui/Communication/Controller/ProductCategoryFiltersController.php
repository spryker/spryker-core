<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFiltersController extends AbstractController
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

        return $this->viewResponse([
            'idCategory' => $idCategory,
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
