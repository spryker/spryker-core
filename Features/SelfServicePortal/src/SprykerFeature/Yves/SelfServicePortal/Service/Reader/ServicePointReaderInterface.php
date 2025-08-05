<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;

interface ServicePointReaderInterface
{
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): string;
}
