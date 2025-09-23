<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Controller\StorefrontApi\RestApi;

use Generated\Shared\Transfer\RestSspInquiriesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \SprykerFeature\Glue\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspInquiriesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *         "summary": [
     *             "Retrieves inquiry data by id."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responses": {
     *             "404": "Inquiry not found.",
     *             "500": "Unexpected error."
     *         }
     *     },
     *     "getCollection": {
     *         "summary": [
     *             "Retrieves all inquiries for the authenticated company user."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $resourceId = $restRequest->getResource()->getId();
        $inquiriesReader = $this->getFactory()->createInquiriesReader();

        if ($resourceId !== null) {
            return $inquiriesReader->getSspInquiry($restRequest);
        }

        return $inquiriesReader->getSspInquiries($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *         "summary": [
     *             "Creates an inquiry."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responses": {
     *             "409": "Inquiry integrity error.",
     *             "422": "Unprocessable inquiry.",
     *             "500": "Unexpected error."
     *         }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestSspInquiriesAttributesTransfer $restSspInquiriesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestSspInquiriesAttributesTransfer $restSspInquiriesAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createInquiriesCreator()
            ->create($restRequest, $restSspInquiriesAttributesTransfer);
    }
}
