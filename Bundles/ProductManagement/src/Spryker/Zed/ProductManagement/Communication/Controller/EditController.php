<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\ProductManagement\Business\Product\MatrixGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 */
class EditController extends AddController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            self::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $productAbstractTransfer = $this->getFactory()
            ->getProductManagementFacade()
            ->getProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage(sprintf('The product [%s] you are trying to edit, does not exist.', $idProductAbstract));

            return new RedirectResponse('/product-management');
        }

        $dataProvider = $this->getFactory()->createProductFormEditDataProvider();
        $form = $this
            ->getFactory()
            ->createProductFormEdit(
                $dataProvider->getData($idProductAbstract),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $concreteProductCollection = $this->getFactory()
            ->getProductManagementFacade()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);

        if ($form->isValid()) {
            try {
                $productAbstractTransfer = $this->buildProductAbstractTransferFromData($form->getData());
                $productAbstractTransfer->setIdProductAbstract($idProductAbstract);
                $attributeCollection = $this->getFactory()->getProductAttributeCollection();
                $attributes = $this->getAttributesFromData($form->getData(), $attributeCollection);

                $matrixGenerator = new MatrixGenerator();
                $matrix = $matrixGenerator->generate($productAbstractTransfer, $attributes);

                $idProductAbstract = $this->getFactory()
                    ->getProductManagementFacade()
                    ->saveProduct($productAbstractTransfer, $matrix);

                $this->addSuccessMessage(sprintf(
                    'The product [%s] was saved successfully.',
                    $idProductAbstract
                ));

                return $this->redirectResponse(sprintf(
                    '/product-management/edit?%s=%d' ,
                    self::PARAM_ID_PRODUCT_ABSTRACT,
                    $idProductAbstract
                ));
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'currentProduct' => $productAbstractTransfer->toArray(),
            'matrix' => [],
            'concretes' => $concreteProductCollection
        ]);
    }



}
