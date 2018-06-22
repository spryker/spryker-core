<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider;

use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form\ProductListMerchantRelationForm;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProductListFormMerchantRelationExpanderPlugin;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface;

class ProductListDataProvider
{
    /** @see \Spryker\Zed\ProductListGui\Communication\Form\ProductListForm::OPTION_OWNER_TYPES */
    protected const OPTION_OWNER_TYPES = 'OPTION_OWNER_TYPES';
    protected const OWNER_TYPES_NAME = 'Merchant Relation';

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(
        MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getOptions(array $options): array
    {
        $merchantRelationTransfers = $this->merchantRelationshipFacade->listMerchantRelation();

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
        $options = $this->addOwnerTypeNameOption($options);

        return $options;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function addOwnerTypeNameOption(array $options): array
    {
        $ownerTypeNames = $options[static::OPTION_OWNER_TYPES];
        $ownerTypeNames[static::OWNER_TYPES_NAME] = ProductListFormMerchantRelationExpanderPlugin::FIELD_NAME;
        $options[static::OPTION_OWNER_TYPES] = $ownerTypeNames;

        return $options;
    }
}
