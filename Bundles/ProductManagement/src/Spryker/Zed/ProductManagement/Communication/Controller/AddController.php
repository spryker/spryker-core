<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\ProductManagement\Business\Product\MatrixGenerator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createProductFormAddDataProvider();
        $form = $this
            ->getFactory()
            ->createProductFormAdd(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $attributeCollection = $this->normalizeAttributeArray(
            $this->getFactory()->getProductAttributeCollection()
        );

        $localeProvider = $this->getFactory()->createLocaleProvider();

        if ($form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductAbstractTransfer($form);


                $data = $form->getData();

                $attributeValues = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->generateVariantAttributeArrayFromData($data, $attributeCollection);

                $matrixGenerator = new MatrixGenerator();
                $concreteProductCollection = $matrixGenerator->generate($productAbstractTransfer, $attributeValues);

                $idProductAbstract = $this->getFactory()
                    ->getProductManagementFacade()
                    ->addProduct($productAbstractTransfer, $concreteProductCollection);

                $this->addSuccessMessage('The product was added successfully.');

                return $this->redirectResponse(sprintf(
                    '/product-management/edit?%s=%d',
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
            'matrix' => [],
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true)
        ]);
    }

    /**
     * @param array $keys
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributeValues(array $keys, array $attributes)
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] =  $attributes[$key];
        }

        return $values;
    }

    /**
     * @param array $attributeCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function normalizeAttributeArray(array $attributeCollection)
    {
        $attributeArray = [];
        foreach ($attributeCollection as $attributeTransfer) {
            $attributeArray[$attributeTransfer->getKey()] = $attributeTransfer;
        }

        return $attributeArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[] $metadataCollection
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $attributeCollection
     *
     * @return array
     */
    protected function getLocalizedAttributeMetadataNames(array $metadataCollection, array $attributeCollection)
    {
        $currentLocale = (int)$this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale()
            ->getIdLocale();

        $result = [];
        foreach ($metadataCollection as $type => $transfer) {
            $result[$type] = $type;
            if (!isset($attributeCollection[$type])) {
                continue;
            }

            $attributeTransfer = $attributeCollection[$type];
            foreach ($attributeTransfer->getLocalizedAttributes() as $localizedAttribute) {
                if ((int)$localizedAttribute->getFkLocale() === $currentLocale) {
                    $result[$type] = $localizedAttribute->getName();
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param array $concreteProductCollection
     *
     * @return array
     */
    protected function someToView($idProductAbstract, array $concreteProductCollection)
    {
        $r = [];
        foreach ($concreteProductCollection as $t) {
            $c = $t->toArray(true);
            ;
            $c['attributes'] = $this->getFacade()->getProductAttributesByAbstractProductId($idProductAbstract);
            $r[] = $c;
        }

        return $r;
    }

}
