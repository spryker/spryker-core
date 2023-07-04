<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffer;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;

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
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantProductOffer\PHPMD)
 */
class MerchantProductOfferBusinessTester extends Actor
{
    use _generated\MerchantProductOfferBusinessTesterActions;

    /**
     * @var array
     */
    public const FIELDS_TO_REMOVE = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param array $productOffer
     *
     * @return array
     */
    public function removeDynamicProductOfferFields(array $productOffer): array
    {
        return array_diff_key($productOffer, array_flip(static::FIELDS_TO_REMOVE));
    }

    /**
     * @return void
     */
    public function ensureProductOfferTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferQuery());
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
