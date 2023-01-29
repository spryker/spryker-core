<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    /**
     * @var string
     */
    protected const PARAM_CUSTOMER = 'customerTransfer';

    /**
     * @var string
     */
    protected const PARAM_SERIALIZED_CUSTOMER_TRANSFER = 'serializedCustomerTransfer';

    /**
     * @var string
     */
    protected const URL_CUSTOMER_LIST_PAGE = '/customer';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_CUSTOMER_NOT_EXIST = 'Customer with id `%s` does not exist';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        if (!$idCustomer) {
            return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
        }

        $idCustomer = $this->castId($idCustomer);

        $customerTransfer = $this->findCustomerById($idCustomer);

        if ($customerTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_ERROR_CUSTOMER_NOT_EXIST, ['%s' => $idCustomer]);

            return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
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
     * @deprecated Use {@link addressTableAction()} instead.
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function renderCustomerDetailBlocks(Request $request, CustomerTransfer $customerTransfer)
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);

        $subRequest = $this->setCustomerFallback($subRequest, $customerTransfer);

        $subRequest->request->set(static::PARAM_SERIALIZED_CUSTOMER_TRANSFER, $customerTransfer->serialize());

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
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @param \Symfony\Component\HttpFoundation\Request $subRequest
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function setCustomerFallback(Request $subRequest, CustomerTransfer $customerTransfer): Request
    {
        // symfony/http-foundation: <6.0.0
        if (method_exists(JsonResponse::class, 'create')) {
            /** @phpstan-var array $customerTransfer */
            $subRequest->request->set(static::PARAM_CUSTOMER, $customerTransfer);
        }

        return $subRequest;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $blockUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|string|false
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
