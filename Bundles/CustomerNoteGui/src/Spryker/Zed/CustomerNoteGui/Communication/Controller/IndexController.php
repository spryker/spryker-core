<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Controller;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerNoteGui\Communication\CustomerNoteGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    protected const PARAM_ID_CUSTOMER = 'id-customer';
    protected const SUCCESS_MESSAGE = 'Comment successfully added';
    protected const REFERER_HEADER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomer = 1;
//        TODO: use id from request
//        $idCustomer = $request->query->get(self::PARAM_ID_CUSTOMER);

        $formDataProvider = $this->getFactory()->createNoteFormDataProvider();
        $form = $this->getFactory()->getNoteForm(
            $formDataProvider->getData($idCustomer),
            $formDataProvider->getOptions($idCustomer)
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFactory()->createNoteHandler()->handleNoteAddition(
                $idCustomer,
                $form->getData()[SpyCustomerNoteEntityTransfer::MESSAGE]
            );
            $this->addSuccessMessage(static::SUCCESS_MESSAGE);

            return $this->redirectResponse($request->headers->get(self::REFERER_HEADER));
        }

        foreach ($form->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
