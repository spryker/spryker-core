<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\CategoriesBackendApi\CategoriesBackendApiConfig;
use Spryker\Glue\CategoriesBackendApi\Controller\CategoriesResourceController;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;

class CategoriesBackendApiResource extends AbstractResourcePlugin implements JsonApiResourceInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CategoriesBackendApiConfig::RESOURCE_TYPE_CATEGORIES;
    }

    /**
     * @uses \Spryker\Glue\CategoriesBackendApi\Controller\CategoriesResourceController
     *
     * @return string
     */
    public function getController(): string
    {
        return CategoriesResourceController::class;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        $resourceMethodConfiguration = (new GlueResourceMethodConfigurationTransfer())
            ->setAttributes(ApiCategoryAttributesTransfer::class);

        return (new GlueResourceMethodCollectionTransfer())
            ->setGetCollection($resourceMethodConfiguration)
            ->setGet($resourceMethodConfiguration)
            ->setPost($resourceMethodConfiguration)
            ->setPatch($resourceMethodConfiguration)
            ->setDelete($resourceMethodConfiguration);
    }
}
