<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Form\DataProvider;

use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetFormDataProviderInterface
{
    /**
     * @param string $sspAssetReference
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer|null
     */
    public function getData(string $sspAssetReference): ?SspAssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(SspAssetTransfer $sspAssetTransfer): array;
}
