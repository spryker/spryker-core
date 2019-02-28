<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Plugin;

use Generated\Shared\Transfer\RestNavigationAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\NavigationsRestApiExtension\Dependency\Plugin\NavigationsResourceExpanderPluginInterface;

/**
 * @method \Spryker\Glue\UrlsRestApi\UrlsRestApiFactory getFactory()
 */
class CategoryNodeNavigationsResourceExpanderPlugin extends AbstractPlugin implements NavigationsResourceExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Expands navigations resource with information about the assigned category.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestNavigationAttributesTransfer $restNavigationAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestNavigationAttributesTransfer
     */
    public function expand(
        RestNavigationAttributesTransfer $restNavigationAttributesTransfer,
        RestRequestInterface $restRequest
    ): RestNavigationAttributesTransfer {
        return $this->getFactory()
            ->createCategoryNodeNavigationResourceExpander()
            ->expand($restNavigationAttributesTransfer);
    }
}
