<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductAttributesBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines product attribute resource name.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PRODUCT_ATTRIBUTES = 'product-attributes';

    /**
     * Specification:
     * - Defines response code when product attribute is not found.
     *
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_FOUND = '4202';

    /**
     * Specification:
     * - Defines response code when product attribute key is not unique.
     *
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_ATTRIBUTE_KEY_EXISTS = '4203';

    /**
     * Specification:
     * - Defines response code when product attribute key is not provided.
     *
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_PROVIDED = '4204';

    /**
     * Specification:
     * - Defines a message when product attribute is not found.
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_NOT_FOUND = 'Attribute not found.';

    /**
     * Specification:
     * - Defines a message when product attribute key is not unique.
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_KEY_EXISTS = 'Attribute key must be unique.';

    /**
     * Specification:
     * - Defines a message when product attribute key is not provided.
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_NOT_PROVIDED = 'Attribute key is not provided.';

    /**
     * Specification:
     * - Defines a default limit for fetching product attribute.
     *
     * @api
     *
     * @var int
     */
    public const PRODUCT_ATTRIBUTE_DEFAULT_LIMIT = 100;
}
