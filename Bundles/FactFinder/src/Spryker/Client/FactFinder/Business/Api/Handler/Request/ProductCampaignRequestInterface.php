<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\FactFinderProductCampaignRequestTransfer;

interface ProductCampaignRequestInterface
{

    /**
     * @param \Generated\Shared\Transfer\FactFinderProductCampaignRequestTransfer $factFinderProductCampaignRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderProductCampaignResponseTransfer
     */
    public function request(FactFinderProductCampaignRequestTransfer $factFinderProductCampaignRequestTransfer);

}
