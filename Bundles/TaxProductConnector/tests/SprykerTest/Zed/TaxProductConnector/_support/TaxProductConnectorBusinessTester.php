<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Shared\Tax\TaxConstants;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class TaxProductConnectorBusinessTester extends Actor
{
    use _generated\TaxProductConnectorBusinessTesterActions;

    /**
     * @param float $currentTaxRate
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function createTaxRateWithTaxSetInDb(float $currentTaxRate, string $iso2Code): TaxSetTransfer
    {
        $idCountry = SpyCountryQuery::create()->filterByIso2Code($iso2Code)->findOne()->getIdCountry();

        return $this->haveTaxSetWithTaxRates([], [
            [
                TaxRateTransfer::FK_COUNTRY => $idCountry,
                TaxRateTransfer::RATE => $currentTaxRate,
            ],
            [
                TaxRateTransfer::FK_COUNTRY => $idCountry,
                TaxRateTransfer::RATE => 5.00,
            ],
            [
                TaxRateTransfer::FK_COUNTRY => $idCountry,
                TaxRateTransfer::NAME => TaxConstants::TAX_EXEMPT_PLACEHOLDER,
                TaxRateTransfer::RATE => 0.00,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer|null $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createProductWithTaxSetInDb(?TaxSetTransfer $taxSetTransfer): ProductAbstractTransfer
    {
        $productAbstractOverride = [];
        if ($taxSetTransfer !== null) {
            $productAbstractOverride[ProductAbstractTransfer::ID_TAX_SET] = $taxSetTransfer->getIdTaxSet();
        }

        $productAbstractTransfer = $this->haveProductAbstract($productAbstractOverride);

        return $productAbstractTransfer;
    }

    /**
     * @param string $countryIso2Code
     * @param \Generated\Shared\Transfer\TaxSetTransfer[] $taxSetTransferList
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSetByAddressIso2CodeInTaxSetTransferList(
        string $countryIso2Code,
        array $taxSetTransferList = []
    ): ?TaxSetTransfer {
        if (!isset($taxSetTransferList[$countryIso2Code])) {
            return null;
        }

        return $taxSetTransferList[$countryIso2Code];
    }
}
