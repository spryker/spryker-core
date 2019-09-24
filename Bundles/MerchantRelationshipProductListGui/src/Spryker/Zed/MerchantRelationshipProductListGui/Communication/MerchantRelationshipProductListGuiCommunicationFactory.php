<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\MerchantRelationshipChoiceFormDataProvider;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListMerchantRelationshipFormDataProvider;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form\MerchantRelationshipChoiceFormType;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander\ProductListMerchantRelationshipCreateFormExpander;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander\ProductListMerchantRelationshipCreateFormExpanderInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander\ProductListMerchantRelationshipEditFormExpander;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander\ProductListMerchantRelationshipEditFormExpanderInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\ProductListQueryExpander\ProductListQueryExpander;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\ProductListQueryExpander\ProductListQueryExpanderInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Persistence\MerchantRelationshipProductListGuiRepositoryInterface getRepository()
 */
class MerchantRelationshipProductListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\MerchantRelationshipChoiceFormDataProvider
     */
    public function createMerchantRelationshipChoiceFormDataProvider(): MerchantRelationshipChoiceFormDataProvider
    {
        return new MerchantRelationshipChoiceFormDataProvider($this->getMerchantRelationshipFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form\MerchantRelationshipChoiceFormType
     */
    public function createMerchantRelationshipChoiceFormType(): MerchantRelationshipChoiceFormType
    {
        return new MerchantRelationshipChoiceFormType();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipProductListGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface
     */
    public function getMerchantRelationshipProductListFacade(): MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipProductListGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\ProductListQueryExpander\ProductListQueryExpanderInterface
     */
    public function createProductListQueryExpander(): ProductListQueryExpanderInterface
    {
        return new ProductListQueryExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListMerchantRelationshipFormDataProvider
     */
    public function createProductListMerchantRelationshipFormDataProvider(): ProductListMerchantRelationshipFormDataProvider
    {
        return new ProductListMerchantRelationshipFormDataProvider(
            $this->getMerchantRelationshipProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander\ProductListMerchantRelationshipEditFormExpanderInterface
     */
    public function createProductListMerchantRelationshipEditFormExpander(): ProductListMerchantRelationshipEditFormExpanderInterface
    {
        return new ProductListMerchantRelationshipEditFormExpander(
            $this->createProductListMerchantRelationshipFormDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander\ProductListMerchantRelationshipCreateFormExpanderInterface
     */
    public function createProductListMerchantRelationshipCreateFormExpander(): ProductListMerchantRelationshipCreateFormExpanderInterface
    {
        return new ProductListMerchantRelationshipCreateFormExpander(
            $this->createProductListMerchantRelationshipFormDataProvider()
        );
    }
}
