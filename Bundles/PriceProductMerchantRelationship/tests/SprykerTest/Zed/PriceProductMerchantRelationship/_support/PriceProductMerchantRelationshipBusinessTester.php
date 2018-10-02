<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationship;

use Codeception\Actor;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 */
class PriceProductMerchantRelationshipBusinessTester extends Actor
{
    use _generated\PriceProductMerchantRelationshipBusinessTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param string $merchantRelationshipKey
     * @param string|null $companyBusinessUnitOwnerKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function createMerchantRelationship(
        string $merchantRelationshipKey,
        ?string $companyBusinessUnitOwnerKey = null
    ): MerchantRelationshipTransfer {
        $merchant = $this->haveMerchant();

        $companyBusinessUnitSeed = $companyBusinessUnitOwnerKey ? ['key' => $companyBusinessUnitOwnerKey] : [];
        $companyBusinessUnitOwner = $this->haveCompanyBusinessUnit($companyBusinessUnitSeed);

        return $this->haveMerchantRelationship([
            'fkMerchant' => $merchant->getIdMerchant(),
            'merchant' => $merchant,
            'fkCompanyBusinessUnit' => $companyBusinessUnitOwner->getIdCompanyBusinessUnit(),
            'merchantRelationshipKey' => $merchantRelationshipKey,
            'ownerCompanyBusinessUnit' => $companyBusinessUnitOwner,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductMerchantRelationship(): PriceProductTransfer
    {
        $priceProductTransfer = (new PriceProductBuilder())
            ->withMoneyValue()
            ->withPriceDimension()
            ->withPriceType()
            ->build();

        $priceProductTransfer->fromArray(
            $this->getPriceProductFacade()->findProductAbstractPrice(1)->modifiedToArray()
        );

        $merchantRelationshipTransfer = $this->createMerchantRelationship('key');

        $priceProductTransfer
            ->getPriceDimension()
            ->setIdMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship());

        return $this->getFacade()->savePriceProductMerchantRelationship($priceProductTransfer);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->getLocator()->priceProduct()->facade();
    }
}
