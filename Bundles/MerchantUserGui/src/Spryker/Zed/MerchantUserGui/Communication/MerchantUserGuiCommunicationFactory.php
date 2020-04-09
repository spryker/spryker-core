<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantUserGui\Communication\Form\Constraint\UniqueEmailConstraint;
use Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider\MerchantUserUpdateFormDataProvider;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserCreateForm;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserDeleteConfirmForm;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserUpdateForm;
use Spryker\Zed\MerchantUserGui\Communication\Table\MerchantUserTable;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToRouterFacadeInterface;
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
        return new MerchantUserTable(
            $this->getMerchantUserPropelQuery(),
            $this->getRouterFacade(),
            $idMerchant
        );
    }

    /**
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    public function getMerchantUserPropelQuery(): SpyMerchantUserQuery
    {
        return $this->getProvidedDependency(MerchantUserGuiDependencyProvider::PROPEL_QUERY_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider\MerchantUserUpdateFormDataProvider
     */
    public function createMerchantUserUpdateFormDataProvider(): MerchantUserUpdateFormDataProvider
    {
        return new MerchantUserUpdateFormDataProvider($this->getMerchantUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Communication\Form\Constraint\UniqueEmailConstraint
     */
    public function createUniqueEmailConstraint(): UniqueEmailConstraint
    {
        return new UniqueEmailConstraint(
            [UniqueEmailConstraint::OPTION_MERCHANT_USER_FACADE => $this->getMerchantUserFacade()]
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUserDeleteConfirmForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUserDeleteConfirmForm::class);
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantUserGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToRouterFacadeInterface
     */
    public function getRouterFacade(): MerchantUserGuiToRouterFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserGuiDependencyProvider::FACADE_ROUTER);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUserCreateForm(UserTransfer $userTransfer, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUserCreateForm::class, $userTransfer, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUserUpdateForm(UserTransfer $userTransfer, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUserUpdateForm::class, $userTransfer, $options);
    }
}
