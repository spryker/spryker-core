<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CmsPageStorageTransfer;
use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;
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
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer $cmsPageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageRestResponse(CmsPageStorageTransfer $cmsPageStorageTransfer): RestResponseInterface
    {
        $restCmsPagesAttributesTransfer = (new RestCmsPagesAttributesTransfer())
            ->fromArray($cmsPageStorageTransfer->toArray(), true);

        $cmsPageRestResource = $this->createCmsPageRestResource($cmsPageStorageTransfer->getUuid(), $restCmsPagesAttributesTransfer);

        return $this->restResourceBuilder->createRestResponse()->addResource($cmsPageRestResource);
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
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer[] $cmsPageStorageTransfers
     * @param int $totalPagesFound
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageCollectionRestResponse(array $cmsPageStorageTransfers, int $totalPagesFound): RestResponseInterface
    {
        $restCmsPagesAttributesTransfers = $this->cmsPageMapper
            ->mapCmsPageStorageTransfersToRestCmsPagesAttributesTransfers($cmsPageStorageTransfers);

        $response = $this->restResourceBuilder->createRestResponse($totalPagesFound);

        foreach ($restCmsPagesAttributesTransfers as $cmsPageUuid => $restCmsPagesAttributesTransfer) {
            $response->addResource($this->createCmsPageRestResource($cmsPageUuid, $restCmsPagesAttributesTransfer));
        }

        return $response;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param string $cmsPageUuid
     * @param \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCmsPageRestResource(
        string $cmsPageUuid,
        RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            CmsPagesRestApiConfig::RESOURCE_CMS_PAGES,
            $cmsPageUuid,
            $restCmsPagesAttributesTransfer
        );
    }
}
