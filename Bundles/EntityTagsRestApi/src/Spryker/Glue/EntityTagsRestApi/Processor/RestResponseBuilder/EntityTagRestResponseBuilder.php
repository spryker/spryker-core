<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class EntityTagRestResponseBuilder implements EntityTagRestResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createPreconditionRequiredError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(EntityTagsRestApiConfig::RESPONSE_CODE_IF_MATCH_HEADER_MISSING)
            ->setDetail(EntityTagsRestApiConfig::RESPONSE_DETAIL_IF_MATCH_HEADER_MISSING)
            ->setStatus(Response::HTTP_PRECONDITION_REQUIRED);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createPreconditionFailedError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(EntityTagsRestApiConfig::RESPONSE_CODE_IF_MATCH_HEADER_INVALID)
            ->setDetail(EntityTagsRestApiConfig::RESPONSE_DETAIL_IF_MATCH_HEADER_INVALID)
            ->setStatus(Response::HTTP_PRECONDITION_FAILED);
    }
}
