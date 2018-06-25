<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTableConstantsInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 */
abstract class AbstractProductPackagingUnitGuiController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_CREATE = 'Product packaging unit type created successfully.';
    protected const MESSAGE_ERROR_PACKAGING_UNIT_TYPE_CREATE = 'Product packaging unit type has not been created.';

    protected const MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_DELETE = 'Product packaging type "%s" successfully deleted.';
    protected const MESSAGE_ERROR_PACKAGING_UNIT_TYPE_DELETE = 'Product packaging unit type "%s" has not been deleted.';

    protected const MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_UPDATE = 'Product packaging type "%s" successfully updated.';
    protected const MESSAGE_ERROR_PACKAGING_UNIT_TYPE_UPDATE = 'Product packaging unit type "%s" has not been updated.';

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    protected function findProductPackagingUnitTypeById(
        int $idProductPackagingUnitType
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeTransfer())
            ->setIdProductPackagingUnitType($idProductPackagingUnitType);

        return $this->getFactory()
            ->getProductPackagingUnitFacade()
            ->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getSuccessRedirectUrl(Request $request): string
    {
        if ($request->query->get(static::PARAM_REDIRECT_URL)) {
            return $request->query->get(static::PARAM_REDIRECT_URL);
        }

        return ProductPackagingUnitTypeTableConstantsInterface::URL_PRODUCT_PACKAGING_UNIT_TYPE_LIST;
    }
}
