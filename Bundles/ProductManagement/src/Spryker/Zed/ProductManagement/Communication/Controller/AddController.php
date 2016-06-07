<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
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

        if ($form->isValid()) {
            try {
                $productAbstractTransfer = $this->buildProductAbstractTransferFromData($form->getData());

                $idProductAbstract = $this->getFactory()->getProductFacade()->createProductAbstract($productAbstractTransfer);
                $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

                $this->addSuccessMessage('The product was added successfully.');

                return $this->redirectResponse(sprintf(
                    '/product/edit?%s=%d' ,
                    self::PARAM_ID_PRODUCT_ABSTRACT,
                    $idProductAbstract
                ));
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName()
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function buildProductAbstractTransferFromData(array $formData)
    {
        //TODO get definition of products attributes here
        $abstractAttributes = [
            'attribute_foo' => 'foo',
            'attribute_bar' => 'bar'
        ];

        $abstractLocalizedAttributes = [
            'de_DE' => [
                'attribute_foo' => 'foo_de',
                'attribute_bar' => 'bar_bar'
            ],
            'en_US' => [
                'attribute_foo' => 'foo_en',
                'attribute_bar' => 'bar_en'
            ],
        ];

        $productAbstractTransfer = $this->createProductTransfer($formData);
        $productAbstractTransfer->setAttributes($abstractAttributes);

        $attributeData = $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES];
        foreach ($attributeData as $localeCode => $localizedAttributesData) {
            $localeTransfer = $this->getFactory()->getLocaleFacade()->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createLocalizedAttributesTransfer(
                $localizedAttributesData,
                $abstractLocalizedAttributes[$localeCode],
                $localeTransfer
            );

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductTransfer(array $data)
    {
        $productAbstractTransfer = new ProductAbstractTransfer();

        $productAbstractTransfer->setSku(
            $data[ProductFormAdd::FIELD_SKU]
        );

        $productAbstractTransfer->setIsActive(false);

        return $productAbstractTransfer;
    }

    /**
     * @param array $data
     * @param array $abstractLocalizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer(array $data, array $abstractLocalizedAttributes, LocaleTransfer $localeTransfer)
    {
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($data[ProductFormAdd::FIELD_NAME]);
        $localizedAttributesTransfer->setAttributes($abstractLocalizedAttributes);

        return $localizedAttributesTransfer;
    }

}
