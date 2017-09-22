<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication;

use Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionDataProvider;
use Spryker\Zed\Money\Communication\Form\Transformer\MoneyAmountTransformer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Money\Communication\Form\Type\MoneyType;
use Spryker\Zed\Money\MoneyDependencyProvider;

/**
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 * @method \Spryker\Zed\Money\Business\MoneyFacadeInterface getFacade()
 */
class MoneyCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Money\Communication\Form\Transformer\MoneyAmountTransformer|\Symfony\Component\Form\DataTransformerInterface
     */
    public function createCurrencyAmountTransformer()
    {
        return new MoneyAmountTransformer($this->getFacade());
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\Type\MoneyType
     */
    public function createMoneyFormType()
    {
        return new MoneyType();
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionDataProvider
     */
    public function createMoneyCollectionDataProvider()
    {
        return new MoneyCollectionDataProvider($this->getCurrencyFacade());
    }

    /**
     * @return \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::FACADE_CURRENCY);
    }
}
