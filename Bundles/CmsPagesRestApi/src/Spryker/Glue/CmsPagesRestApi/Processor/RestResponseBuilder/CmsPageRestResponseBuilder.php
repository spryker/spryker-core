<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestCmsPageAttributesTransfer;
use Generated\Shared\Transfer\RestCmsPagesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig;
use Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPageMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CmsPageRestResponseBuilder implements CmsPageRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPageMapperInterface
     */
    protected $cmsPageMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPageMapperInterface $cmsPageMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CmsPageMapperInterface $cmsPageMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cmsPageMapper = $cmsPageMapper;
    }

    /**
     * @param string $cmsPageUuid
     * @param \Generated\Shared\Transfer\RestCmsPageAttributesTransfer $restCmsPageAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageRestResponse(string $cmsPageUuid, RestCmsPageAttributesTransfer $restCmsPageAttributesTransfer): RestResponseInterface
    {
        $cmsPageRestResource = $this->createCmsPageRestResource($cmsPageUuid, $restCmsPageAttributesTransfer);

        return $this->restResourceBuilder->createRestResponse()->addResource($cmsPageRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageNotFoundErrorRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CmsPagesRestApiConfig::RESPONSE_CODE_CMS_PAGE_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CmsPagesRestApiConfig::RESPONSE_DETAIL_CMS_PAGE_NOT_FOUND)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer[] $searchResult
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageCollectionRestResponse(array $searchResult): RestResponseInterface
    {
        $restCmsPagesAttributesTransfer = $this->cmsPageMapper
            ->mapSearchResultToRestCmsPagesTransfer($searchResult, new RestCmsPagesTransfer());

        $response = $this->restResourceBuilder->createRestResponse($restCmsPagesAttributesTransfer->getPagination()->getNumFound());

        foreach ($restCmsPagesAttributesTransfer->getRestCmsPagesAttributes() as $restCmsPageAttributesTransfer) {
            $response->addResource($this->createCmsPageRestResource($restCmsPageAttributesTransfer->getUuid(), $restCmsPageAttributesTransfer));
        }

        return $response;
    }

    /**
     * @param string $cmsPageUuid
     * @param \Generated\Shared\Transfer\RestCmsPageAttributesTransfer $restCmsPageAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCmsPageRestResource(
        string $cmsPageUuid,
        RestCmsPageAttributesTransfer $restCmsPageAttributesTransfer
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            CmsPagesRestApiConfig::RESOURCE_CMS_PAGES,
            $cmsPageUuid,
            $restCmsPageAttributesTransfer
        );
    }
}
