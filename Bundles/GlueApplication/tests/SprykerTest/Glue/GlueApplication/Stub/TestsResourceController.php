<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Stub;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class TestsResourceController extends AbstractRestController
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(): RestResponseInterface
    {
        $restRequest = $this->getRestRequest();

        $restResponse = $this->createRestResourceBuilder()->createRestResponse(20);

        $restTestAttributesTransfer = (new RestTestAttributesTransfer())
            ->setAttribute1('attribute1');

        $restResource = $this->createRestResourceBuilder()
            ->createRestResource('tests', 1, $restTestAttributesTransfer);

        $restResponse->addResource($restResource);

        return $restResponse;
    }

    /**
     * @param \SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer $restTestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestTestAttributesTransfer $restTestAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->createRestResourceBuilder()->createRestResponse();

        $requestResource = $this->getRestRequest()->getResource();

        if (!$restTestAttributesTransfer->getAttribute1()) {
            $restErrorTransfer = new RestErrorMessageTransfer();
            $restErrorTransfer
                ->setCode(1)
                ->setDetail('Invalid data')
                ->setStatus(Response::HTTP_BAD_REQUEST);
            $restResponse->addError($restErrorTransfer);

            return $restResponse;
        }

        $responseResource = $this->createRestResourceBuilder()
            ->createRestResource(
                $requestResource->getType(),
                1,
                $requestResource->getAttributes()
            );

        $restResponse->addResource($responseResource);

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(): RestResponseInterface
    {
        return $this->createRestResourceBuilder()->createRestResponse();
    }

    /**
     * @param \SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer $restTestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestTestAttributesTransfer $restTestAttributesTransfer): RestResponseInterface
    {
        return $this->postAction($restTestAttributesTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected function createRestResourceBuilder(): RestResourceBuilderInterface
    {
        return new RestResourceBuilder();
    }
}
