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
 */
class ViewController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        if (empty($idCustomer)) {
            return $this->redirectResponse('/customer');
        }

        $idCustomer = $this->castId($idCustomer);

        $customerTransfer = $this->loadCustomerTransfer($idCustomer);

        $addressTable = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        $externalBlocks = $this->renderCustomerDetailBlocks($request, $customerTransfer);

        return [
            'customer' => $customerTransfer,
            'idCustomer' => $idCustomer,
            'addressTable' => $addressTable->render(),
            'blocks' => $externalBlocks,
        ];
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
     * @return array
     */
    protected function renderCustomerDetailBlocks(Request $request, CustomerTransfer $customerTransfer)
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);
        $subRequest->request->set('customerTransfer', $customerTransfer);

        $responseData = [];
        $blocks = $this->getFactory()->getCustomerDetailExternalBlocksUrls();

        foreach ($blocks as $blockName => $blockUrl) {
            $responseData[$blockName] = $this->handleSubRequest($subRequest, $blockUrl);
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
        $blockResponse = $this->getSubRequestHandler()->handleSubRequest($request, $blockUrl);
        if ($blockResponse instanceof RedirectResponse) {
            return $blockResponse;
        }

        return $blockResponse->getContent();
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Request\SubRequestHandlerInterface
     */
    protected function getSubRequestHandler()
    {
        return $this->getApplication()['sub_request'];
    }
}
