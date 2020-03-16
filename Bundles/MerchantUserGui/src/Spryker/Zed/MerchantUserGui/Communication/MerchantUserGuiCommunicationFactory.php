<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication;

use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider\MerchantUserCreateFormDataProvider;
use Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider\MerchantUserUpdateFormDataProvider;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserCreateForm;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserUpdateForm;
use Spryker\Zed\MerchantUserGui\Communication\Table\MerchantUserTable;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToUserFacadeInterface;
use Spryker\Zed\MerchantUserGui\MerchantUserGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class MerchantUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int $idMerchant
     *
     * @return \Spryker\Zed\MerchantUserGui\Communication\Table\MerchantUserTable
     */
    public function createMerchantUserTable(int $idMerchant): MerchantUserTable
    {
        return new MerchantUserTable($this->getMerchantUserPropelQuery(), $idMerchant);
    }

    /**
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    public function getMerchantUserPropelQuery(): SpyMerchantUserQuery
    {
        return $this->getProvidedDependency(MerchantUserGuiDependencyProvider::PROPEL_QUERY_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider\MerchantUserCreateFormDataProvider
     */
    public function createMerchantUserCreateFormDataProvider(): MerchantUserCreateFormDataProvider
    {
        return new MerchantUserCreateFormDataProvider($this->getMerchantUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider\MerchantUserUpdateFormDataProvider
     */
    public function createMerchantUserUpdateFormDataProvider(): MerchantUserUpdateFormDataProvider
    {
        return new MerchantUserUpdateFormDataProvider($this->getMerchantUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantUserGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToUserFacadeInterface
     */
    public function getUserFacade(): MerchantUserGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUserCreateForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUserCreateForm::class, $data, $options);
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUserUpdateForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUserUpdateForm::class, $data, $options);
    }
}
