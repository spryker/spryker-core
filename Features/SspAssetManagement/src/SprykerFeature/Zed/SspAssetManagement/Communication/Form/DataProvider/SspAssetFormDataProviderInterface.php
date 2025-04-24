<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetFormDataProviderInterface
{
    /**
     * @param int $sspAssetId
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer|null
     */
    public function getData(int $sspAssetId): ?SspAssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(SspAssetTransfer $sspAssetTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return string|null
     */
    public function getAssetImageUrl(SspAssetTransfer $sspAssetTransfer): ?string;

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $submittedFormData
     *
     * @return array<string, mixed>
     */
    public function expandOptionsWithSubmittedData(array $options, array $submittedFormData): array;
}
