<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication;

use Generated\Shared\Transfer\DataTablesTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Communication\Form\CollectorPluginForm;
use Spryker\Zed\Discount\Communication\Form\DataProvider\CartRuleFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherCodesFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DecisionRuleForm;
use Spryker\Zed\Discount\Communication\Form\Transformers\DecisionRulesFormTransformer;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\Communication\Table\VoucherPoolCategoryTable;
use Spryker\Zed\Discount\Communication\Table\VoucherPoolTable;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Zend\Filter\Word\CamelCaseToUnderscore;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVoucherForm(array $formData = [], array $formOptions = [])
    {
        $voucherForm = new VoucherForm($this->getConfig());

        return $this->getFormFactory()->create($voucherForm, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider
     */
    public function createVoucherFormDataProvider()
    {
        return new VoucherFormDataProvider($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\VoucherPoolCategoryTable
     */
    public function createPoolCategoriesTable()
    {
        $poolCategoriesQuery = $this->getQueryContainer()->queryDiscountVoucherPoolCategory();

        return new VoucherPoolCategoryTable($poolCategoriesQuery);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountsTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return new DiscountsTable($discountQuery);
    }

    /**
     * @param \Generated\Shared\Transfer\DataTablesTransfer $dataTablesTransfer
     * @param int $idPool
     * @param int $batchValue
     *
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable
     */
    public function createDiscountVoucherCodesTable(DataTablesTransfer $dataTablesTransfer, $idPool, $batchValue)
    {
        return new DiscountVoucherCodesTable(
            $dataTablesTransfer,
            $this->getQueryContainer(),
            $idPool,
            $batchValue
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\VoucherPoolTable
     */
    public function createVoucherPoolTable()
    {
        $poolQuery = $this->getQueryContainer()->queryDiscountVoucherPool();

        return new VoucherPoolTable($poolQuery, $this->getConfig());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCartRuleForm(array $formData = [], array $formOptions = [])
    {
        $cartRuleForm = new CartRuleForm($this->getConfig(), $this->createDecisionRulesFormTransformer());

        return $this->getFormFactory()->create($cartRuleForm, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\CartRuleFormDataProvider
     */
    public function createCartRuleFormDataProvider()
    {
        return new CartRuleFormDataProvider($this->getFacade());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCollectorPluginForm()
    {
        $collectorPluginForm = new CollectorPluginForm($this->getConfig());

        return $this->getFormFactory()->create($collectorPluginForm);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVoucherCodesForm(array $formData = [], array $formOptions = [])
    {
        $voucherCodesForm = new VoucherCodesForm($this->getConfig(), $this->createDecisionRulesFormTransformer());

        return $this->getFormFactory()->create($voucherCodesForm, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\Transformers\DecisionRulesFormTransformer
     */
    public function createDecisionRulesFormTransformer()
    {
        return new DecisionRulesFormTransformer($this->getConfig(), $this->createCamelCaseToUnderscoreFilter());
    }

    /**
     * @return \Zend\Filter\Word\CamelCaseToUnderscore
     */
    public function createCamelCaseToUnderscoreFilter()
    {
        return new CamelCaseToUnderscore();
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherCodesFormDataProvider
     */
    public function createVoucherCodesFormDataProvider()
    {
        return new VoucherCodesFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDecisionRuleForm()
    {
        $decisionRulesForm = new DecisionRuleForm($this->getConfig());

        return $this->getFormFactory()->create($decisionRulesForm);
    }

    /**
     * @param int $idPool
     *
     * @return \Generated\Shared\Transfer\VoucherPoolTransfer
     */
    public function getVoucherPoolById($idPool)
    {
        $pool = $this->getQueryContainer()
            ->queryDiscountVoucherPool()
            ->findOneByIdDiscountVoucherPool($idPool);

        return (new VoucherPoolTransfer())->fromArray($pool->toArray(), true);
    }

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function getDiscountById($idDiscount)
    {
        $discount = $this->getQueryContainer()
            ->queryDiscount()
            ->filterByIdDiscount($idDiscount)
            ->findOne();

        return (new DiscountTransfer())->fromArray($discount->toArray(), true);
    }

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function getDiscountByIdDiscountVoucherPool($idDiscountVoucherPool)
    {
        $discount = $this->getQueryContainer()
            ->queryDiscount()
            ->filterByFkDiscountVoucherPool($idDiscountVoucherPool)
            ->findOne();

        return (new DiscountTransfer())->fromArray($discount->toArray(), true);
    }

    /**
     * @param int $idPool
     *
     * @return int
     */
    public function getGeneratedVouchersCountByIdPool($idPool)
    {
        return $this->getQueryForGeneratedVouchersByIdPool($idPool)
            ->count();
    }

    /**
     * @param int $idPool
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function getQueryForGeneratedVouchersByIdPool($idPool)
    {
        return $this->getQueryContainer()
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($idPool);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

}
