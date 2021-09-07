<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductLabel;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ProductLabelConstants
{
    /**
     * @var int
     */
    public const RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER = 1;
    /**
     * @var string
     */
    public const RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY = 'product_label_dictionary';
    /**
     * @var string
     */
    public const RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS = 'product_abstract_product_label_relations';

    /**
     * Specification:
     * - Defines the number of product label relations in the chunk to be deassigned.
     *
     * @api
     * @var string
     */
    public const PRODUCT_LABEL_TO_DE_ASSIGN_CHUNK_SIZE = 'PRODUCT_LABEL:PRODUCT_LABEL_TO_DE_ASSIGN_CHUNK_SIZE';
}
