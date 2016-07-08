<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\FactFinder\Business\Api\ApiConstants;

class ProductCampaignRequest extends AbstractRequest implements RequestInterface
{
    
    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_PRODUCT_CAMPAIGN;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FFProductCampaignResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $productCampaignRequestTransfer = $quoteTransfer->getFFProductCampaignRequest();

        // @todo @Artem : check do we need send request? 
        // $request = mapper->map($searchRequestTransfer);
        $productCampaignAdapter = $this->ffConnector->createProductCampaignAdapter();
        // @todo check
        $campaigns = $productCampaignAdapter->getCampaigns();
        
        $this->logInfo($quoteTransfer, $productCampaignAdapter);
        
        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createProductCampaignResponseConverter($productCampaignAdapter)
            ->convert();

        return $responseTransfer;
    }

}
