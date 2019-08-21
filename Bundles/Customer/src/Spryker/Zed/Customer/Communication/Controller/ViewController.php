<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class ViewController extends AbstractController
{
    protected const PARAM_CUSTOMER = 'customerTransfer';

    protected const URL_REDIRECT_CUSTOMER_NOT_EXISTS = '/customer';
    protected const MESSAGE_ERROR_CUSTOMER_NOT_EXIST = 'Customer with id %s does not exist';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        if (empty($idCustomer)) {
            return $this->redirectResponse(static::URL_REDIRECT_CUSTOMER_NOT_EXISTS);
        }

        $idCustomer = $this->castId($idCustomer);

        $customerTransfer = $this->findCustomerById($idCustomer);

        if ($customerTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_ERROR_CUSTOMER_NOT_EXIST, ['%s' => $idCustomer]);

            return $this->redirectResponse(static::URL_REDIRECT_CUSTOMER_NOT_EXISTS);
        }

        $addressTable = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        $externalBlocks = $this->renderCustomerDetailBlocks($request, $customerTransfer);
        if ($externalBlocks instanceof RedirectResponse) {
            return $externalBlocks;
        }

        return $this->viewResponse([
            'customer' => $customerTransfer,
            'idCustomer' => $idCustomer,
            'addressTable' => $addressTable->render(),
            'blocks' => $externalBlocks,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addressTableAction(Request $request)
    {
        $idCustomer = $this->castId($request->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $table = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @deprecated Use addressTableAction() instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        return $this->addressTableAction($request);
    }

    /**
     * @deprecated Use `ViewController::findCustomer()` instead.
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function loadCustomerTransfer($idCustomer)
    {
        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer = $this->getFacade()->getCustomer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function renderCustomerDetailBlocks(Request $request, CustomerTransfer $customerTransfer)
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);
        $subRequest->request->set(static::PARAM_CUSTOMER, $customerTransfer);

        $responseData = [];
        $blocks = $this->getFactory()->getCustomerDetailExternalBlocksUrls();

        foreach ($blocks as $blockName => $blockUrl) {
            $blockResponse = $this->handleSubRequest($subRequest, $blockUrl);
            if ($blockResponse instanceof RedirectResponse) {
                return $blockResponse;
            }

            $responseData[$blockName] = $blockResponse;
        }

        return $responseData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $blockUrl
     *
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubRequest(Request $request, $blockUrl)
    {
        $blockResponse = $this->getFactory()->getSubRequestHandler()->handleSubRequest($request, $blockUrl);
        if ($blockResponse instanceof RedirectResponse) {
            return $blockResponse;
        }

        return $blockResponse->getContent();
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomerById(int $idCustomer): ?CustomerTransfer
    {
        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        return $this->getFacade()->findCustomerById($customerTransfer);
    }
}
