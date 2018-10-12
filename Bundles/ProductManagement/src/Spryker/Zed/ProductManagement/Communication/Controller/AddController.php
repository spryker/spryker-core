<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class AddController extends AbstractController
{
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const PARAM_ID_PRODUCT = 'id-product';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createProductFormAddDataProvider();

        $type = $request->query->get('type');

        $form = $this
            ->getFactory()
            ->createProductFormAdd(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $localeProvider = $this->getFactory()->createLocaleProvider();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductAbstractTransfer($form, null);

                $concreteProductCollection = $this->createProductConcreteCollection(
                    $type,
                    $productAbstractTransfer,
                    $form
                );

                $idProductAbstract = $this->getFactory()
                    ->getProductFacade()
                    ->addProduct($productAbstractTransfer, $concreteProductCollection);

                $this->addSuccessMessage(sprintf(
                    'The product [%s] was added successfully.',
                    $productAbstractTransfer->getSku()
                ));

                return $this->createRedirectResponseAfterAdd($idProductAbstract);
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'concreteProductCollection' => [],
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'productFormAddTabs' => $this->getFactory()->createProductFormAddTabs()->createView(),
            'type' => $type,
        ]);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponseAfterAdd($idProductAbstract)
    {
        return $this->redirectResponse(sprintf(
            '/product-management/edit?%s=%d',
            self::PARAM_ID_PRODUCT_ABSTRACT,
            $idProductAbstract
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bundledProductTableAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get('id-product-concrete'));

        $bundledProductTable = $this->getFactory()
            ->createBundledProductTable($idProductConcrete);

        return $this->jsonResponse(
            $bundledProductTable->fetchData()
        );
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
            $values[$key] = $attributes[$key];
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
     * @param string $type
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function createProductConcreteCollection(
        $type,
        ProductAbstractTransfer $productAbstractTransfer,
        FormInterface $form
    ) {
        if ($type === ProductManagementConfig::PRODUCT_TYPE_BUNDLE) {
            $productConcreteTransfer = new ProductConcreteTransfer();
            $productConcreteTransfer->setSku($productAbstractTransfer->getSku());
            $productConcreteTransfer->setIsActive(false);
            $productConcreteTransfer->setPrices($productAbstractTransfer->getPrices());
            $productConcreteTransfer->setLocalizedAttributes($productAbstractTransfer->getLocalizedAttributes());

            return [$productConcreteTransfer];
        }

        $attributeCollection = $this->normalizeAttributeArray($this->getFactory()->getProductAttributeCollection());

        $attributeValues = $this->getFactory()
            ->createProductFormTransferGenerator()
            ->generateVariantAttributeArrayFromData($form->getData(), $attributeCollection);

        $concreteProductCollection = $this->getFactory()
            ->getProductFacade()
            ->generateVariants($productAbstractTransfer, $attributeValues);

        return $concreteProductCollection;
    }
}
