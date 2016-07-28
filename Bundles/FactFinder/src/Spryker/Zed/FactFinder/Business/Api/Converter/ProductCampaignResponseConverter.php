<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\ProductCampaign as FFProductCampaign;
use Generated\Shared\Transfer\FactFinderSearchResponseTransfer;

class ProductCampaignResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\ProductCampaign
     */
    protected $productCampaignAdapter;

    /**
     * @param \FACTFinder\Adapter\ProductCampaign $productCampaignAdapter
     */
    public function __construct(FFProductCampaign $productCampaignAdapter)
    {
        $this->productCampaignAdapter = $productCampaignAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FactFinderSearchResponseTransfer();
//        $responseTransfer->set();

        return $responseTransfer;
    }

}
