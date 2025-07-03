<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductApproval\Business\Expander\ProductAbstractExpander;
use Spryker\Zed\ProductApproval\Business\Expander\ProductAbstractExpanderInterface;
use Spryker\Zed\ProductApproval\Business\Extractor\QuoteOriginalSalesOrderItemExtractor;
use Spryker\Zed\ProductApproval\Business\Extractor\QuoteOriginalSalesOrderItemExtractorInterface;
use Spryker\Zed\ProductApproval\Business\Filter\ProductAbstractStorageCollectionFilter;
use Spryker\Zed\ProductApproval\Business\Filter\ProductAbstractStorageCollectionFilterInterface;
use Spryker\Zed\ProductApproval\Business\Filter\ProductConcreteCollectionFilter;
use Spryker\Zed\ProductApproval\Business\Filter\ProductConcreteCollectionFilterInterface;
use Spryker\Zed\ProductApproval\Business\Filter\ProductConcreteStorageCollectionFilter;
use Spryker\Zed\ProductApproval\Business\Filter\ProductConcreteStorageCollectionFilterInterface;
use Spryker\Zed\ProductApproval\Business\Filter\ProductPageSearchCollectionFilter;
use Spryker\Zed\ProductApproval\Business\Filter\ProductPageSearchCollectionFilterInterface;
use Spryker\Zed\ProductApproval\Business\Filter\QuoteItemsFilter;
use Spryker\Zed\ProductApproval\Business\Filter\QuoteItemsFilterInterface;
use Spryker\Zed\ProductApproval\Business\Reader\ApplicableApprovalStatusReader;
use Spryker\Zed\ProductApproval\Business\Reader\ApplicableApprovalStatusReaderInterface;
use Spryker\Zed\ProductApproval\Business\Reader\ProductReader;
use Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface;
use Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalCartChangeValidator;
use Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalCartChangeValidatorInterface;
use Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalCheckoutValidator;
use Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalCheckoutValidatorInterface;
use Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalShoppingListValidator;
use Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalShoppingListValidatorInterface;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToMessengerFacadeInterface;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;
use Spryker\Zed\ProductApproval\ProductApprovalDependencyProvider;

/**
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 */
class ProductApprovalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductApproval\Business\Reader\ApplicableApprovalStatusReaderInterface
     */
    public function createApplicableApprovalStatusReader(): ApplicableApprovalStatusReaderInterface
    {
        return new ApplicableApprovalStatusReader($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Filter\ProductAbstractStorageCollectionFilterInterface
     */
    public function createProductAbstractStorageCollectionFilter(): ProductAbstractStorageCollectionFilterInterface
    {
        return new ProductAbstractStorageCollectionFilter($this->createProductReader());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Filter\ProductConcreteStorageCollectionFilterInterface
     */
    public function createProductConcreteStorageCollectionFilter(): ProductConcreteStorageCollectionFilterInterface
    {
        return new ProductConcreteStorageCollectionFilter($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Filter\ProductPageSearchCollectionFilterInterface
     */
    public function createProductPageSearchCollectionFilter(): ProductPageSearchCollectionFilterInterface
    {
        return new ProductPageSearchCollectionFilter($this->createProductReader());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Filter\ProductConcreteCollectionFilterInterface
     */
    public function createProductConcreteCollectionFilter(): ProductConcreteCollectionFilterInterface
    {
        return new ProductConcreteCollectionFilter($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalCartChangeValidatorInterface
     */
    public function createProductApprovalCartChangeValidator(): ProductApprovalCartChangeValidatorInterface
    {
        return new ProductApprovalCartChangeValidator($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Extractor\QuoteOriginalSalesOrderItemExtractorInterface
     */
    public function createQuoteOriginalSalesOrderItemExtractor(): QuoteOriginalSalesOrderItemExtractorInterface
    {
        return new QuoteOriginalSalesOrderItemExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Filter\QuoteItemsFilterInterface
     */
    public function createQuoteItemsFilter(): QuoteItemsFilterInterface
    {
        return new QuoteItemsFilter(
            $this->createProductReader(),
            $this->getMessengerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalCheckoutValidatorInterface
     */
    public function createProductApprovalCheckoutValidator(): ProductApprovalCheckoutValidatorInterface
    {
        return new ProductApprovalCheckoutValidator($this->createProductReader());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface
     */
    public function createProductReader(): ProductReaderInterface
    {
        return new ProductReader($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Expander\ProductAbstractExpanderInterface
     */
    public function createProductAbstractExpander(): ProductAbstractExpanderInterface
    {
        return new ProductAbstractExpander($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalShoppingListValidatorInterface
     */
    public function createProductApprovalShoppingListValidator(): ProductApprovalShoppingListValidatorInterface
    {
        return new ProductApprovalShoppingListValidator($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;
     */
    public function getProductFacade(): ProductApprovalToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductApprovalDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ProductApprovalToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ProductApprovalDependencyProvider::FACADE_MESSENGER);
    }
}
