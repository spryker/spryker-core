<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiFacade getFacade()
 * @method \Spryker\Zed\AvailabilityGui\Communication\AvailabilityGuiCommunicationFactory getFactory()
 */
class ProductBundleController extends AbstractController
{

    const URL_PARAM_ID_PRODUCT_CONCRETE = 'id-product';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->query->getInt(static::URL_PARAM_ID_PRODUCT_CONCRETE));

        return [
            'idProductAbstract' => 1,
        ];
    }
}
