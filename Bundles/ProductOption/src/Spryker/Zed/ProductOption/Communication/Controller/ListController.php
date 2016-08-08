<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class ListController extends IndexController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $productOptionListTable = $this->getFactory()->createProductOptionListTable();

        return [
            'listTable' => $productOptionListTable->render(),
        ];
    }

    /***
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listTableAction()
    {
        $productOptionListTable = $this->getFactory()->createProductOptionListTable();

        return $this->jsonResponse(
            $productOptionListTable->fetchData()
        );
    }

}
