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
            ->setCode(EntityTagsRestApiConfig::RESPONSE_CODE_PRECONDITION_REQUIRED)
            ->setDetail(EntityTagsRestApiConfig::RESPONSE_DETAIL_PRECONDITION_REQUIRED)
            ->setStatus(Response::HTTP_PRECONDITION_REQUIRED);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createPreconditionFailedError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(EntityTagsRestApiConfig::RESPONSE_CODE_PRECONDITION_FAILED)
            ->setDetail(EntityTagsRestApiConfig::RESPONSE_DETAIL_PRECONDITION_FAILED)
            ->setStatus(Response::HTTP_PRECONDITION_FAILED);
    }
}
