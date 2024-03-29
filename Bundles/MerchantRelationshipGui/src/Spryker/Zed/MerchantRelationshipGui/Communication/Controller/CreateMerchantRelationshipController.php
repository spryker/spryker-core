<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipGui\Communication\Table\MerchantRelationshipTableConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class CreateMerchantRelationshipController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    public const PARAM_SUBMIT_PERSIST = 'submit-persist';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_RELATION_CREATE_SUCCESS = 'Merchant relation created successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_RELATION_CREATE_ERROR = 'Merchant relation has not been created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $isSubmitPersist = (bool)$request->request->get(static::PARAM_SUBMIT_PERSIST, false);

        $dataProvider = $this->getFactory()->createMerchantRelationshipFormDataProvider();
        $idCompany = $this->getCompanyIdFromRequest($request);
        $merchantRelationshipForm = $this->getFactory()
            ->getMerchantRelationshipCreateForm(
                $dataProvider->getData(),
                $dataProvider->getOptions($isSubmitPersist, $idCompany),
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function createMerchantRelationship(Request $request, FormInterface $merchantRelationshipForm)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);
        /** @var \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer */
        $merchantRelationshipTransfer = $merchantRelationshipForm->getData();
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())->setMerchantRelationship($merchantRelationshipTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer */
        $merchantRelationshipResponseTransfer = $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->createMerchantRelationship(
                $merchantRelationshipTransfer,
                $merchantRelationshipRequestTransfer,
            );

        if (!$merchantRelationshipResponseTransfer->getIsSuccessfulOrFail()) {
            foreach ($merchantRelationshipResponseTransfer->getErrors() as $merchantRelationshipErrorTransfer) {
                $this->addErrorMessage($merchantRelationshipErrorTransfer->getMessageOrFail());
            }

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
