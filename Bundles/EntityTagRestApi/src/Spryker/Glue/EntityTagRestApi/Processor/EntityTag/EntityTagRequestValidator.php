<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi\Processor\EntityTag;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface;
use Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityTagRequestValidator implements EntityTagRequestValidatorInterface
{
    protected const HEADER_IF_MATCH = 'If-Match';

    /**
     * @var \Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface
     */
    protected $entityTagChecker;

    /**
     * @var \Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @param \Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface $entityTagClient
     */
    public function __construct(EntityTagCheckerInterface $entityTagChecker, EntityTagRestApiToEntityTagClientInterface $entityTagClient)
    {
        $this->entityTagChecker = $entityTagChecker;
        $this->entityTagClient = $entityTagClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        if (!$httpRequest->isMethod(Request::METHOD_PATCH)) {
            return null;
        }

        if (!$this->entityTagChecker->isEntityTagRequired($restRequest->getResource())) {
            return null;
        }

        if (!$httpRequest->headers->has(static::HEADER_IF_MATCH)) {
            return $restErrorCollectionTransfer->addRestError(
                $this->createPreconditionRequiredError()
            );
        }
        $entityTag = $this->entityTagClient->read(
            $restRequest->getResource()->getType(),
            $restRequest->getResource()->getId()
        );

        if ($entityTag !== $httpRequest->headers->get(static::HEADER_IF_MATCH)) {
            return $restErrorCollectionTransfer->addRestError(
                $this->createPreconditionFailedError()
            );
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createPreconditionRequiredError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(EntityTagRestApiConfig::RESPONSE_CODE_PRECONDITION_REQUIRED)
            ->setDetail(EntityTagRestApiConfig::RESPONSE_DETAIL_PRECONDITION_REQUIRED)
            ->setStatus(Response::HTTP_PRECONDITION_REQUIRED);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createPreconditionFailedError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(EntityTagRestApiConfig::RESPONSE_CODE_PRECONDITION_FAILED)
            ->setDetail(EntityTagRestApiConfig::RESPONSE_DETAIL_PRECONDITION_FAILED)
            ->setStatus(Response::HTTP_PRECONDITION_FAILED);
    }
}
