<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
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
    protected const CUSTOMER_PARAM_SERIALIZED = 'serializedCustomerTransfer';

    /**
     * @var string
     */
    protected const CUSTOMER_ID_PARAM = 'customer-id';

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
        $customerTransfer = $ths->getCustomerTransfer();

        $idCustomer = $customerTransfer !== null
            ? $customerTransfer->getIdCustomer()
            : $request->request->get(static::CUSTOMER_ID_PARAM);

        $idCustomer = $this->castId($idCustomer);

        $formDataProvider = $this->getFactory()->createNoteFormDataProvider();
        $form = $this->getFactory()->getNoteForm(
            $formDataProvider->getData($idCustomer),
            $formDataProvider->getOptions(),
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFactory()->getCustomerNoteFacade()->addNoteFromCurrentUser(
                $this->createSpyCustomerNoteEntityTransfer(
                    $idCustomer,
                    $form->getData()->getMessage(),
                ),
            );
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse($request->headers->get(static::REFERER_HEADER));
        }

        /** @var array<\Symfony\Component\Form\FormError> $errors */
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomerTransfer(Request $request): ?CustomerTransfer
    {
        // @deprecated Exists for BC reasons. Will be removed in the next major release.
        if ($request->request->has(static::CUSTOMER_PARAM)) {
            /** @phpstan-var \Generated\Shared\Transfer\CustomerTransfer */
            return $request->request->get(static::CUSTOMER_PARAM);
        }

        if (!$request->request->has(static::CUSTOMER_PARAM_SERIALIZED)) {
            return null;
        }

        $orderTransfer = new CustomerTransfer();
        $orderTransfer->unserialize((string)$request->request->get(static::CUSTOMER_PARAM_SERIALIZED));

        return $orderTransfer;
    }
}
