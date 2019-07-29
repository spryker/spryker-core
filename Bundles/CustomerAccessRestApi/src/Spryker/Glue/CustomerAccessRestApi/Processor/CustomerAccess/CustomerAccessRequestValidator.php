<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\PermissionAwareTrait;
use Symfony\Component\HttpFoundation\Response;

class CustomerAccessRequestValidator implements CustomerAccessRequestValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig
     */
    protected $customerAccessRestApiConfig;

    /**
     * @param \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig $customerAccessRestApiConfig
     */
    public function __construct(CustomerAccessRestApiConfig $customerAccessRestApiConfig)
    {
        $this->customerAccessRestApiConfig = $customerAccessRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $resourceType = $restRequest->getResource()->getType();
        if (!$this->customerAccessRestApiConfig->hasPluginNameByResourceType($resourceType)) {
            return null;
        }

        if ($this->can($this->customerAccessRestApiConfig->getPluginNameByResourceType($resourceType))) {
            return null;
        }

        return (new RestErrorCollectionTransfer())
            ->addRestError($this->createNotFoundErrorMessageTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createNotFoundErrorMessageTransfer(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
    }
}
