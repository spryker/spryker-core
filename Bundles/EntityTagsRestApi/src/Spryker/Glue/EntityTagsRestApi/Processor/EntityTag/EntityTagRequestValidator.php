<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTagCheckerInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder\EntityTagRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class EntityTagRequestValidator implements EntityTagRequestValidatorInterface
{
    protected const HEADER_IF_MATCH = 'If-Match';

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagCheckerInterface
     */
    protected $entityTagChecker;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder\EntityTagRestResponseBuilderInterface
     */
    protected $entityTagRestResponseBuilder;

    /**
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface $entityTagClient
     */
    public function __construct(EntityTagCheckerInterface $entityTagChecker, EntityTagsRestApiToEntityTagClientInterface $entityTagClient, EntityTagRestResponseBuilderInterface $entityTagRestResponseBuilder)
    {
        $this->entityTagChecker = $entityTagChecker;
        $this->entityTagClient = $entityTagClient;
        $this->entityTagRestResponseBuilder = $entityTagRestResponseBuilder;
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
                $this->entityTagRestResponseBuilder->createPreconditionRequiredError()
            );
        }
        $entityTag = $this->entityTagClient->read(
            $restRequest->getResource()->getType(),
            $restRequest->getResource()->getId()
        );

        if ($entityTag === null || $entityTag !== $httpRequest->headers->get(static::HEADER_IF_MATCH)) {
            return $restErrorCollectionTransfer->addRestError(
                $this->entityTagRestResponseBuilder->createPreconditionFailedError()
            );
        }

        return null;
    }
}
