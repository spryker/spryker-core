<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;

/**
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
     * @return void
     */
    public function truncateProductOffers(): void
    {
        $this->truncateTableRelations($this->getProductOfferPropelQuery());
    }

    /**
     * @return void
     */
    public function truncateProductOfferStores(): void
    {
        $this->truncateTableRelations($this->getProductOfferStorePropelQuery());
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery
     */
    protected function getProductOfferStorePropelQuery(): SpyProductOfferStoreQuery
    {
        return SpyProductOfferStoreQuery::create();
    }
}
