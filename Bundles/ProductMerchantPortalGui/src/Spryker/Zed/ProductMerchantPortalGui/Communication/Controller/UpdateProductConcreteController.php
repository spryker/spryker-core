<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;


use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductConcreteController extends ProductMerchantPortalAbstractController
{
    protected const PARAM_PRODUCT_ID = 'product-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProduct = $this->castId($request->get(static::PARAM_PRODUCT_ID));
        $formData = $this->getFactory()->createProductConcreteEditFormDataProvider()->getData($idProduct);
        $productConcreteTransfer = $formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE];

        if (!$formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE]) {
            throw new ProductConcreteNotFoundException($idProduct);
        }

        $productConcreteEditForm = $this->getFactory()->createProductConcreteEditForm($formData);
        $productConcreteEditForm->handleRequest($request);

        if ($productConcreteEditForm->isSubmitted() && $productConcreteEditForm->isValid()) {
            $this->getFactory()->getProductFacade()->saveProductConcrete(
                $formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE]
            );
        }

        return $this->getResponse($productConcreteEditForm, $productConcreteTransfer);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productConcreteEditForm
     *
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productConcreteEditForm,
        ProductConcreteTransfer $productConcreteTransfer
    ): JsonResponse {
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $localizedAttributesTransfer = $this->getFactory()->createLocalizedAttributesExtractor()->extractLocalizedAttributes(
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer
        );
        $superAttributeNames = $this->getFactory()->createLocalizedAttributesExtractor()->extractCombinedSuperAttributeNames(
            $productConcreteTransfer->getAttributes(),
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer
        );

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_concrete_form.twig', [
                'form' => $productConcreteEditForm->createView(),
                'productConcrete' => $productConcreteTransfer,
                'productConcreteName' => $localizedAttributesTransfer ? $localizedAttributesTransfer->getName() : $productConcreteTransfer->getName(),
                'productAttributeTableConfiguration' => $this->getFactory()
                    ->createProductConcreteAttributeGuiTableConfigurationProvider()
                    ->getConfiguration($productConcreteTransfer->getAttributes(), array_keys($superAttributeNames), $productConcreteTransfer->getLocalizedAttributes()),
                'superAttributeNames' => $superAttributeNames,
            ])->getContent(),
        ];

        if (!$productConcreteEditForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productConcreteEditForm->isValid()) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData);

            return new JsonResponse($responseData);
        }

        $responseData = $this->addErrorResponseDataToResponse($responseData);

        return new JsonResponse($responseData);
    }
}