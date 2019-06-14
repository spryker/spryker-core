<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiFactory getFactory()
 */
class EntityTagRestRequestValidatorPlugin extends AbstractPlugin implements RestRequestValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Compares resource hash stored in key-value storage with 'If-Match' header from request.
     *  - Adds the 428 'Precondition required' error if resource required ETag validation and request does not contain the 'If-Match' header.
     *  - Adds the 412 'Precondition failed' error if resource required ETag validation and a request contains invalid 'If-Match' header.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        return $this->getFactory()
            ->createEntityTagRequestValidator()
            ->validate($httpRequest, $restRequest);
    }
}
