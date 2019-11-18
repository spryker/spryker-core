<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductOfferDataImportCommunicationTester extends Actor
{
    use _generated\MerchantProductOfferDataImportCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return void
     */
    public function truncateProductOffers(): void
    {
        $this->truncateTableRelations($this->getProductOfferPropelQuery());
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
