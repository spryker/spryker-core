<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 */
class ListController extends IndexController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $productOptionListTable = $this->getFactory()->createProductOptionListTable();

        $viewData = [
            'listTable' => $productOptionListTable->render(),
        ];

        return $this->viewResponse(
            $this->expandViewData($viewData),
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listTableAction()
    {
        $productOptionListTable = $this->getFactory()->createProductOptionListTable();

        return $this->jsonResponse(
            $productOptionListTable->fetchData(),
        );
    }

    /**
     * @param array $viewData
     *
     * @return array
     */
    protected function expandViewData(array $viewData): array
    {
        $productOptionListActionViewDataExpanderPlugins = $this->getFactory()
            ->getProductOptionListActionViewDataExpanderPlugins();

        foreach ($productOptionListActionViewDataExpanderPlugins as $productOptionListActionViewDataExpanderPlugin) {
            $viewData = $productOptionListActionViewDataExpanderPlugin->expand($viewData);
        }

        return $viewData;
    }
}
