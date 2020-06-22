<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CmsPagesContentProductAbstractListsResourceRelationshipConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::TWIG_FUNCTION_NAME
     */
    public const TWIG_FUNCTION_NAME = 'content_product_abstract_list';

    /**
     * @uses \Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS
     */
    public const RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS = 'content-product-abstract-lists';
}
