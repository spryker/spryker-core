<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Search\Reader;

use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer;

interface SspAssetSearchReaderInterface
{
    public function getSspAssetSearchCollection(SspAssetSearchCriteriaTransfer $sspAssetSearchCriteriaTransfer): SspAssetSearchCollectionTransfer;
}
