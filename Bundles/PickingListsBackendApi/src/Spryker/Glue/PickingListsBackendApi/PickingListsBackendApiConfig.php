<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class PickingListsBackendApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DEFAULT_LOCALE = 'en_US';

    /**
     * Specification:
     * - Resource name of picking lists path.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PICKING_LISTS = 'picking-lists';

    /**
     * Specification:
     * - Resource name of picking list items path.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PICKING_LIST_ITEMS = 'picking-list-items';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY = 'picking_list.validation.wrong_request_body';

    /**
     * @var string
     */
    public const RESPONSE_CODE_WRONG_REQUEST_BODY = '5301';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_AUTHORIZATION_FAILED = 'Authorization failed.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_AUTHORIZATION_FAILED = '5302';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListExistsPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND = 'picking_list.validation.picking_list_entity_not_found';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_ENTITY_NOT_FOUND = '5303';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemExistsPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND = 'picking_list.validation.picking_list_item_entity_not_found';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_ITEM_ENTITY_NOT_FOUND = '5304';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY = 'picking_list.validation.incorrect_quantity';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_INCORRECT_QUANTITY = '5305';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED = 'picking_list.validation.only_full_quantity_picking_allowed';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_ONLY_FULL_QUANTITY_ALLOWED = '5306';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY = 'picking_list.validation.wrong_property_picking_list_item_quantity';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_MISSING_REQUIRED_PROPERTY_PICKING_LIST_ITEM_QUANTITY = '5307';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED = 'picking_list.validation.wrong_property_picking_list_item_number_of_picked';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED = '5308';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED = 'picking_list.validation.wrong_property_picking_list_item_number_of_not_picked';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED = '5309';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListPickedByAnotherUserPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER = 'picking_list.validation.picked_by_another_user';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListDuplicatedPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED = 'picking_list.validation.picking_list_duplicated';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PICKING_LIST_DUPLICATED = '5311';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemDuplicatedPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED = 'picking_list.validation.picking_list_item_duplicated';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PICKING_LIST_ITEM_DUPLICATED = '5312';

    /**
     * Specification:
     * - Returns a map of glossary keys to REST Error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getValidationGlossaryKeyToRestErrorMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WRONG_REQUEST_BODY,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
            ],
            static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ENTITY_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_NOT_FOUND,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ITEM_ENTITY_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_INCORRECT_QUANTITY,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY,
            ],
            static::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ONLY_FULL_QUANTITY_ALLOWED,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED,
            ],
            static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_MISSING_REQUIRED_PROPERTY_PICKING_LIST_ITEM_QUANTITY,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY,
            ],
            static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED,
            ],
            static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED,
            ],
            static::GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ENTITY_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_NOT_FOUND,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PICKING_LIST_DUPLICATED,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED,
            ],
            static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PICKING_LIST_ITEM_DUPLICATED,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED,
            ],
        ];
    }
}
