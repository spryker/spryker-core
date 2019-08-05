<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Catalog;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CatalogSearchRequestParametersIntegerValidator implements CatalogSearchRequestParametersIntegerValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig
     */
    protected $catalogSearchRestApiConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig $catalogSearchRestApiConfig
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, CatalogSearchRestApiConfig $catalogSearchRestApiConfig)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->catalogSearchRestApiConfig = $catalogSearchRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if ($restRequest->getResource()->getType() !== CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH) {
            return null;
        }

        $requestParameters = $restRequest->getHttpRequest()->query->all();
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        foreach ($this->catalogSearchRestApiConfig->getIntegerRequestParameterNames() as $integerRequestParameterName) {
            $requestParameterValue = $this->getArrayElementByDotNotation(
                $integerRequestParameterName,
                $requestParameters
            );

            if (!$this->isValidInteger($requestParameterValue)) {
                $restErrorCollectionTransfer->addRestError(
                    $this->createErrorMessageTransfer($integerRequestParameterName)
                );
            }
        }

        if ($restErrorCollectionTransfer->getRestErrors()->count()) {
            return $restErrorCollectionTransfer;
        }

        return null;
    }

    /**
     * @param mixed $requestParameterValue
     *
     * @return bool
     */
    protected function isValidInteger($requestParameterValue): bool
    {
        if ($requestParameterValue === '') {
            return false;
        }

        if (($requestParameterValue && filter_var($requestParameterValue, FILTER_VALIDATE_INT) === false)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $requestParameterName
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(string $requestParameterName): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CatalogSearchRestApiConfig::RESPONSE_CODE_PARAMETER_MUST_BE_INTEGER)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(sprintf(CatalogSearchRestApiConfig::ERROR_MESSAGE_PARAMETER_MUST_BE_INTEGER, $requestParameterName));
    }

    /**
     * @param string $key
     * @param array $data
     * @param mixed $default
     *
     * @return mixed|null
     */
    protected function getArrayElementByDotNotation(string $key, array $data, $default = null)
    {
        if (!$key || !$data) {
            return $default;
        }

        if (strpos($key, '.') === false) {
            return $data[$key] ?? $default;
        }

        $keys = explode('.', $key);
        foreach ($keys as $innerKey) {
            if (!array_key_exists($innerKey, $data)) {
                return $default;
            }

            $data = $data[$innerKey];
        }

        return $data;
    }
}
