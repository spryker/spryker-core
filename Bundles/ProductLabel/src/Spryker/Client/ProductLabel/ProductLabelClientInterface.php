<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel;

interface ProductLabelClientInterface
{
    /**
     * Specification:
     * - Finds product labels for the given abstract-product in the key-value storage
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName);

    /**
     * Specification:
     * - Finds a list of product labels by their IDs.
     * - The result contains valid labels from the label dictionary in the given locale.
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName);

    /**
     * Specification:
     * - Finds product label by the given localized name in the key-value storage
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabelByLocalizedName($labelName, $localeName);

    /**
     * Specification:
     * - Finds product label by the given name in the key-value storage
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabelByName($labelName, $localeName);
}
