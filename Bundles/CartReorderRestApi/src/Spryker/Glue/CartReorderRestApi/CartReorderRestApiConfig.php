<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class CartReorderRestApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name for `cart-reorder` endpoint.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_CART_REORDER = 'cart-reorder';

    /**
     * Specification:
     * - Default error code when cart reorder failed.
     * - Part of documentation https://spryker.atlassian.net/wiki/spaces/CORE/pages/226427002/Error+codes
     *
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_DEFAULT_CART_REORDER_FAILED = '5800';

    /**
     * @var string
     */
    protected const ERROR_CODE_ORDER_NOT_FOUND = '5801';

    /**
     * @uses \Spryker\Zed\CartReorder\Business\Creator\CartReorderCreator::GLOSSARY_KEY_ORDER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_NOT_FOUND = 'cart_reorder.validation.order_not_found';

    /**
     * @var string
     */
    protected const ERROR_CODE_QUOTE_NOT_PROVIDED = '5802';

    /**
     * @uses \Spryker\Zed\CartReorder\Business\Creator\CartReorderCreator::GLOSSARY_KEY_QUOTE_NOT_PROVIDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_NOT_PROVIDED = 'cart_reorder.validation.quote_not_provided';

    /**
     * Specification:
     * - Errors map that contains glossary error message in keys and array of rest error code and status in values.
     * - Allows to map error messages from zed gateway to glue.
     *
     * @api
     *
     * @return array<string, array<string, int|string>>
     */
    public function getErrorMessageToRestErrorMapping(): array
    {
        return [
            static::GLOSSARY_KEY_ORDER_NOT_FOUND => [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'code' => static::ERROR_CODE_ORDER_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_QUOTE_NOT_PROVIDED => [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'code' => static::ERROR_CODE_QUOTE_NOT_PROVIDED,
            ],
        ];
    }
}
