<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig;
use Spryker\Glue\CustomerAccessRestApi\Dependency\Client\CustomerAccessRestApiToCustomerAccessStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomerAccessRequestFormatter implements CustomerAccessRequestFormatterInterface
{
    /**
     * @uses \Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthAccessTokenValidator::REQUEST_ATTRIBUTE_IS_PROTECTED
     */
    protected const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';
    protected const REQUEST_ATTRIBUTE_TYPE = 'type';

    /**
     * @var \Spryker\Glue\CustomerAccessRestApi\Dependency\Client\CustomerAccessRestApiToCustomerAccessStorageClientInterface
     */
    protected $customerAccessStorageClient;

    /**
     * @var \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig
     */
    protected $customerAccessRestApiConfig;

    /**
     * @param \Spryker\Glue\CustomerAccessRestApi\Dependency\Client\CustomerAccessRestApiToCustomerAccessStorageClientInterface $customerAccessStorageClient
     * @param \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig $customerAccessRestApiConfig
     */
    public function __construct(
        CustomerAccessRestApiToCustomerAccessStorageClientInterface $customerAccessStorageClient,
        CustomerAccessRestApiConfig $customerAccessRestApiConfig
    ) {
        $this->customerAccessStorageClient = $customerAccessStorageClient;
        $this->customerAccessRestApiConfig = $customerAccessRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function updateResourceIsProtectedFlag(RequestBuilderInterface $requestBuilder, Request $request): RequestBuilderInterface
    {
        $currentResourceCustomerAccessContentType = $this->getCurrentResourceCustomerAccessContentType($request);
        if (!$currentResourceCustomerAccessContentType) {
            return $requestBuilder;
        }

        $customerAccessTransfer = $this->customerAccessStorageClient->getAuthenticatedCustomerAccess();

        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccessTransfer) {
            if ($this->isCustomerAccessContentTypeRestricted($contentTypeAccessTransfer, $currentResourceCustomerAccessContentType)) {
                $request->attributes->set(static::REQUEST_ATTRIBUTE_IS_PROTECTED, true);

                return $requestBuilder;
            }
        }

        return $requestBuilder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string|null
     */
    protected function getCurrentResourceCustomerAccessContentType(Request $request): ?string
    {
        $customerAccessContentTypeToResourceTypeMapping = $this->customerAccessRestApiConfig
            ->getCustomerAccessContentTypeToResourceTypeMapping();
        $currentResourceType = $request->attributes->get(static::REQUEST_ATTRIBUTE_TYPE);

        foreach ($customerAccessContentTypeToResourceTypeMapping as $customerAccessContentType => $resourceTypes) {
            if (in_array($currentResourceType, $resourceTypes)) {
                return $customerAccessContentType;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $contentTypeAccessTransfer
     * @param string $currentResourceCustomerAccessContentType
     *
     * @return bool
     */
    protected function isCustomerAccessContentTypeRestricted(
        ContentTypeAccessTransfer $contentTypeAccessTransfer,
        string $currentResourceCustomerAccessContentType
    ): bool {
        return $contentTypeAccessTransfer->getContentType() === $currentResourceCustomerAccessContentType
            && $contentTypeAccessTransfer->getIsRestricted();
    }
}
