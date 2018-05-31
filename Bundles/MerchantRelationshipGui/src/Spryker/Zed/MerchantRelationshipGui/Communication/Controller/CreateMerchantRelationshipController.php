<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipGui\Communication\Table\MerchantRelationshipTableConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class CreateMerchantRelationshipController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    public const PARAM_SUBMIT_PERSIST = 'submit-persist';

    protected const MESSAGE_MERCHANT_RELATION_CREATE_SUCCESS = 'Merchant relation created successfully.';
    protected const MESSAGE_MERCHANT_RELATION_CREATE_ERROR = 'Merchant relation has not been created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $isSubmitPersist = $request->request->get(static::PARAM_SUBMIT_PERSIST);

        $dataProvider = $this->getFactory()->createMerchantRelationshipFormDataProvider();
        $idCompany = $this->getCompanyIdFromRequest($request);
        $merchantRelationshipForm = $this->getFactory()
            ->getMerchantRelationshipCreateForm(
                $dataProvider->getData(),
                $dataProvider->getOptions($idCompany)
            )
            ->handleRequest($request);

        if ($isSubmitPersist && $merchantRelationshipForm->isSubmitted() && $merchantRelationshipForm->isValid()) {
            return $this->createMerchantRelationship($request, $merchantRelationshipForm);
        }

        return $this->viewResponse([
            'form' => $merchantRelationshipForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createMerchantRelationship(Request $request, FormInterface $merchantRelationshipForm)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);
        $merchantRelationshipTransfer = $merchantRelationshipForm->getData();
        $merchantRelationshipTransfer = $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->createMerchantRelationship($merchantRelationshipTransfer);

        if (!$merchantRelationshipTransfer->getIdMerchantRelationship()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_RELATION_CREATE_ERROR);

            return $this->viewResponse([
                'form' => $merchantRelationshipForm->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_RELATION_CREATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int|null
     */
    protected function getCompanyIdFromRequest(Request $request): ?int
    {
        $formData = $request->get('merchant-relationship', []);

        return array_key_exists('fk_company', $formData) ? $this->castId($formData['fk_company']) : null;
    }
}
