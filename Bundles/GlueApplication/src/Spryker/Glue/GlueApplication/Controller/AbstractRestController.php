<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

abstract class AbstractRestController extends AbstractController
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequest;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function getRestRequest(): RestRequestInterface
    {
        return $this->restRequest;
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findParentResource(string $type): ?RestResourceInterface
    {
        foreach ($this->restRequest->getParentResources() as $restResource) {
            if ($restResource->getType() === $type) {
                return $restResource;
            }
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return $this
     */
    public function setRestRequest(RestRequestInterface $restRequest): self
    {
        $this->restRequest = $restRequest;

        return $this;
    }
}
