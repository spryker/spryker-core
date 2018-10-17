<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface;

class PriceProductDimensionExpander implements PriceProductDimensionExpanderInterface
{
    protected const FORMAT_NAME = '%s - %s';

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(
        PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expand(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setIdMerchantRelationship($priceProductDimensionTransfer->getIdMerchantRelationship());

        $merchantRelationshipTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipById($merchantRelationshipTransfer);

        $priceProductDimensionTransfer->setType(PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP);
        $priceProductDimensionTransfer->setName($this->generateMerchantRelationshipName($merchantRelationshipTransfer));

        return $priceProductDimensionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return string
     */
    protected function generateMerchantRelationshipName(MerchantRelationshipTransfer $merchantRelationshipTransfer): string
    {
        return sprintf(
            static::FORMAT_NAME,
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getName()
        );
    }
}
