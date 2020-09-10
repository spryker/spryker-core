<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig as ConfigurableBundleCartsRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

class ConfigurableBundleCartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CONFIGURED_BUNDLES = 'configured-bundles';
    public const RESOURCE_GUEST_CONFIGURED_BUNDLES = 'guest-configured-bundles';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CARTS
     */
    public const RESOURCE_CARTS = 'carts';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_GUEST_CARTS
     */
    public const RESOURCE_GUEST_CARTS = 'guest-carts';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING
     */
    public const RESPONSE_CODE_CART_ID_MISSING = '104';
    public const RESPONSE_CODE_CONFIGURED_BUNDLE_VALIDATION = '6665'; // TODO: replace it to correct
    public const RESPONSE_CODE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = '6666'; // TODO: replace it to correct
    public const RESPONSE_CODE_CONFIGURED_BUNDLE_WRONG_QUANTITY = '6667'; // TODO: replace it to correct
    public const RESPONSE_CODE_CONFIGURED_BUNDLE_NOT_FOUND = '6668'; // TODO: replace it to correct
    public const RESPONSE_CODE_FAILED_ADDING_CONFIGURED_BUNDLE = '6669'; // TODO: replace it to correct
    public const RESPONSE_CODE_FAILED_UPDATING_CONFIGURED_BUNDLE = '6650'; // TODO: replace it to correct
    public const RESPONSE_CODE_FAILED_REMOVING_CONFIGURED_BUNDLE = '6671'; // TODO: replace it to correct

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
     */
    public const EXCEPTION_MESSAGE_CART_ID_MISSING = 'Cart uuid is missing.';
    public const EXCEPTION_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'Configurable bundle template not found.';
    public const EXCEPTION_MESSAGE_CONFIGURED_BUNDLE_WRONG_QUANTITY = 'The quantity of the configured bundle should be more than zero.';
    public const EXCEPTION_MESSAGE_CONFIGURED_BUNDLE_NOT_FOUND = 'Configured bundle with provided group key not found in cart.';
    public const EXCEPTION_MESSAGE_FAILED_ADDING_CONFIGURED_BUNDLE = 'Configured bundle could not be added.';
    public const EXCEPTION_MESSAGE_FAILED_UPDATING_CONFIGURED_BUNDLE = 'Configured bundle could not be updated.';
    public const EXCEPTION_MESSAGE_FAILED_REMOVING_CONFIGURED_BUNDLE = 'Configured bundle could not be removed.';

    /**
     * @api
     *
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CART_ID_MISSING => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_ID_MISSING,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_ID_MISSING,
            ],
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND,
            ],
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURED_BUNDLE_WRONG_QUANTITY => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CONFIGURED_BUNDLE_WRONG_QUANTITY,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CONFIGURED_BUNDLE_WRONG_QUANTITY,
            ],
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURED_BUNDLE_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CONFIGURED_BUNDLE_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CONFIGURED_BUNDLE_NOT_FOUND,
            ],
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_ADDING_CONFIGURED_BUNDLE => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_ADDING_CONFIGURED_BUNDLE,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_ADDING_CONFIGURED_BUNDLE,
            ],
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_UPDATING_CONFIGURED_BUNDLE => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_UPDATING_CONFIGURED_BUNDLE,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_UPDATING_CONFIGURED_BUNDLE,
            ],
            ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_REMOVING_CONFIGURED_BUNDLE => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_REMOVING_CONFIGURED_BUNDLE,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_REMOVING_CONFIGURED_BUNDLE,
            ],
        ];
    }
}
