<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Stub;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Controller\AbstractController;

class ResourceController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setContent($glueRequestTransfer->getContent())
            ->addResource($glueRequestTransfer->getResource());
    }

    /**
     * @param \SprykerTest\Glue\GlueApplication\Stub\AttributesTransfer $attributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        AttributesTransfer $attributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return (new GlueResponseTransfer())
            ->addResource($glueRequestTransfer->getResource())
            ->setContent($glueRequestTransfer->getContent());
    }

    /**
     * @param string $resourceId
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getByIdAction(string $resourceId, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->addResource($glueRequestTransfer->getResource());
    }
}
