<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Compatibility;

interface AssetProductCompatibilityCheckerInterface
{
    public function isAssetCompatibleToProduct(string $assetReference, int $idProduct): bool;
}
