<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class IndexController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider();

        $generalForm = $this->getFactory()->createGeneralForm($dataProvider->getData(), $dataProvider->getOptions());

        $generalForm->handleRequest($request);

        if ($generalForm->isValid()) {
            $data = $generalForm->getData();
            $idProductOptionGroup = $this->getFacade()->saveProductOptionGroup($data);
        }

        return [
            'generalForm' => $generalForm->createView()
        ];
    }

}
