<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Exception;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomersWriter implements CustomersWriterInterface
{
    const ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    protected $customersResourceMapper;

    /**
     * CustomersWriter constructor.
     *
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface $customersResourceMapper
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersResourceMapperInterface $customersResourceMapper
    ) {
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customersResourceMapper = $customersResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function registerCustomer(RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = $this->customersResourceMapper->mapCustomerAttributesToCustomerTransfer($restRegisterCustomerAttributesTransfer);
        try {
            $customerResponseTransfer = $this->customerClient->registerCustomer($customerTransfer);

            if (!$customerResponseTransfer->getIsSuccess()) {
                foreach ($customerResponseTransfer->getErrors() as $error) {
                    if ($error->getMessage() === CustomersWriter::ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED) {
                        $restErrorTransfer = (new RestErrorMessageTransfer())
                            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ALREADY_EXISTS)
                            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                            ->setDetail(CustomersRestApiConfig::RESPONSE_MESSAGE_CUSTOMER_ALREADY_EXISTS);
                        return $response->addError($restErrorTransfer);
                    }
                    $restErrorTransfer = (new RestErrorMessageTransfer())
                        ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER)
                        ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                        ->setDetail($error->getMessage());
                    $response->addError($restErrorTransfer);
                }
                return $response;
            }
        } catch (Exception $ex) {
            //TODO detect specific exception
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER)
                ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setDetail(CustomersRestApiConfig::RESPONSE_MESSAGE_CUSTOMER_CANT_REGISTER_CUSTOMER);
            return $response->addError($restErrorTransfer);
        }
        $restResource = $this->customersResourceMapper->mapCustomerToCustomersRestResource($customerResponseTransfer->getCustomerTransfer());
        return $response->addResource($restResource);
    }
}
