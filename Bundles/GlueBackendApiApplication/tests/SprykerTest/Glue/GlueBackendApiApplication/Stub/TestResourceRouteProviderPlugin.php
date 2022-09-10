<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Stub;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;

class TestResourceRouteProviderPlugin extends AbstractResourcePlugin implements JsonApiResourceInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'test';
    }

    /**
     * @uses \SprykerTest\Glue\GlueBackendApiApplication\Stub\ResourceController
     *
     * @return string
     */
    public function getController(): string
    {
        return ResourceController::class;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return (new GlueResourceMethodCollectionTransfer())
            ->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction')->setAttributes('\Generated\Shared\Transfer\StoresRestAttributesTransfer'),
            )
            ->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction')->setAttributes('\Generated\Shared\Transfer\StoresRestAttributesTransfer'),
            )
            ->setPost(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('postAction')->setAttributes('\Generated\Shared\Transfer\StoresRestAttributesTransfer'),
            )
            ->setPatch(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('patchAction')->setAttributes('\Generated\Shared\Transfer\StoresRestAttributesTransfer'),
            )
            ->setDelete((new GlueResourceMethodConfigurationTransfer())->setAction('deleteAction'));
    }
}
