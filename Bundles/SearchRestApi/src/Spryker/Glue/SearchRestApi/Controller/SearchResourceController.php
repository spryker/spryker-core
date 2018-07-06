<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Controller;

use Generated\Shared\Transfer\RestSearchRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\SearchRestApi\SearchRestApiFactory getFactory()
 */
class SearchResourceController extends AbstractRestController
{
    /**
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $RestSearchRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestSearchRequestAttributesTransfer $RestSearchRequestAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()->createSearchReader()->search($RestSearchRequestAttributesTransfer);
    }
}
