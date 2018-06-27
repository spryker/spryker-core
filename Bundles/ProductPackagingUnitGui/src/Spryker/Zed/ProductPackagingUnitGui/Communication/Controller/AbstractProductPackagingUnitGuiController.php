<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 */
abstract class AbstractProductPackagingUnitGuiController extends AbstractController
{
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
     * @param string|null $default
     *
     * @return string
     */
    protected function getRequestRedirectUrl(Request $request, ?string $default = null): string
    {
        $redirectUrl = $request->query->get(ProductPackagingUnitGuiConfig::REQUEST_PARAM_REDIRECT_URL);
        if ($redirectUrl) {
            return $redirectUrl;
        }

        return $default ?? ProductPackagingUnitGuiConfig::URL_PRODUCT_PACKAGING_UNIT_TYPE_LIST;
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return string
     */
    protected function getEditPageForId(int $idProductPackagingUnitType): string
    {
        return Url::generate(
            ProductPackagingUnitGuiConfig::URL_PRODUCT_PACKAGING_UNIT_TYPE_EDIT,
            [ProductPackagingUnitGuiConfig::REQUEST_ID_PRODUCT_PACKAGING_UNIT_TYPE => $idProductPackagingUnitType]
        )->build();
    }
}
