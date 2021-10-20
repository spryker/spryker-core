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
    /**
     * @var string
     */
    protected const CUSTOMER_PARAM = 'customerTransfer';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS = 'Comment successfully added';

    /**
     * @var string
     */
    protected const REFERER_HEADER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $request->request->get(static::CUSTOMER_PARAM);
        $idCustomer = (int)$customerTransfer->getIdCustomer();

        $formDataProvider = $this->getFactory()->createNoteFormDataProvider();
        $form = $this->getFactory()->getNoteForm(
            $formDataProvider->getData($idCustomer),
            $formDataProvider->getOptions()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFactory()->getCustomerNoteFacade()->addNoteFromCurrentUser(
                $this->createSpyCustomerNoteEntityTransfer(
                    $idCustomer,
                    $form->getData()->getMessage()
                )
            );
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse($request->headers->get(static::REFERER_HEADER));
        }

        foreach ($form->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }

        return [
            'form' => $form->createView(),
            'notes' => $this->getFactory()->getCustomerNoteFacade()->getNotes($idCustomer)->getNotes(),
        ];
    }

    /**
     * @param int $idCustomer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    protected function createSpyCustomerNoteEntityTransfer(int $idCustomer, string $message)
    {
        $noteTransfer = new SpyCustomerNoteEntityTransfer();
        $noteTransfer->setFkCustomer($idCustomer);
        $noteTransfer->setMessage($message);

        return $noteTransfer;
    }
}
