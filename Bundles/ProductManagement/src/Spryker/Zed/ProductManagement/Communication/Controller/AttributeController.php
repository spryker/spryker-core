<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class AttributeController extends AbstractController
{

    const ID_PRODUCT_ABSTRACT = 'id-attribute';

    /**
     * @return array
     */
    public function indexAction()
    {
    }

    /**
     * @return array
     */
    public function autocompleteAction(Request $request)
    {
        $idAttribute = $this->castId(
            $request->get(self::ID_PRODUCT_ABSTRACT)
        );

        $values = $this->getFacade()->getProductAttributeValues($idAttribute, $searchText);

        return new JsonResponse([
            'id_attribute' => $idAttribute,
            'searchText' => $searchText,
            'values' => []
        ]);
    }

}
