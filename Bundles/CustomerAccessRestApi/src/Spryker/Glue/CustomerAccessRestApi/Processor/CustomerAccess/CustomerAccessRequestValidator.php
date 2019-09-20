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
        $permissionPluginName = $this->customerAccessRestApiConfig->findPermissionPluginNameByResourceType(
            $restRequest->getResource()->getType()
        );
        if (!$permissionPluginName) {
            return null;
        }

        if ($this->can($permissionPluginName)) {
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
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setCode(CustomerAccessRestApiConfig::RESPONSE_CODE_UNAUTHORIZED_ACCESS_FORBIDDEN)
            ->setDetail(CustomerAccessRestApiConfig::RESPONSE_MESSAGE_UNAUTHORIZED_ACCESS_FORBIDDEN);
    }
}
