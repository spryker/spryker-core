<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider;

use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form\ProductListMerchantRelationForm;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface;

class ProductListDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
     */
    protected $facade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $facade
     */
    public function __construct(
        MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $facade
    ) {
        $this->facade = $facade;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getOptions(array $options): array
    {
        $merchantRelationTransfers = $this->facade->listMerchantRelation();

        $merchantRelationNames = [];
        foreach ($merchantRelationTransfers as $merchantRelationTransfer) {
            $name = sprintf(
                '%s - %s',
                $merchantRelationTransfer->getMerchant()->getName(),
                $merchantRelationTransfer->getOwnerCompanyBusinessUnit()->getName()
            );
            $merchantRelationNames[$name] = $merchantRelationTransfer->getIdMerchantRelationship();
        }

        $options[ProductListMerchantRelationForm::OPTION_MERCHANT_RELATION_LIST] = $merchantRelationNames;

        return $options;
    }
}
