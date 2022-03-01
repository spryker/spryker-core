<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Executor;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ResourceExecutor implements ResourceExecutorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function executeResource(
        ResourceInterface $resource,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();
        $executableResource = $resource->getResource($glueRequestTransfer);

        if ($glueRequestTransfer->getContent()) {
            $attributesTransfer = $this->getAttributesTransfer($resource, $glueRequestTransfer);

            if (!$attributesTransfer) {
                return call_user_func($executableResource, $glueRequestTransfer, $glueResponseTransfer);
            }

            $attributesTransfer->fromArray($glueRequestTransfer->getAttributes(), true);
            $glueRequestTransfer->getResource()->setAttributes($attributesTransfer);

            return call_user_func($executableResource, $attributesTransfer, $glueRequestTransfer, $glueResponseTransfer);
        }

        if ($glueRequestTransfer->getResource()->getId()) {
            return call_user_func($executableResource, $glueRequestTransfer->getResource()->getId(), $glueRequestTransfer, $glueResponseTransfer);
        }

        return call_user_func($executableResource, $glueRequestTransfer, $glueResponseTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    protected function getAttributesTransfer(ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): ?AbstractTransfer
    {
        $glueResourceMethodCollectionTransfer = $resource->getDeclaredMethods();

        /** @var \Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer|null $glueResourceMethodConfigurationTransfer */
        $glueResourceMethodConfigurationTransfer = $glueResourceMethodCollectionTransfer
            ->offsetGet($glueRequestTransfer->getResource()->getMethod());

        if ($glueResourceMethodConfigurationTransfer && $glueResourceMethodConfigurationTransfer->getAttributes()) {
            $attributeTransfer = $glueResourceMethodConfigurationTransfer->getAttributesOrFail();
            if (
                is_subclass_of($attributeTransfer, AbstractTransfer::class) &&
                !$attributeTransfer instanceof GlueRequestTransfer
            ) {
                return new $attributeTransfer();
            }
        }

        return null;
    }
}
