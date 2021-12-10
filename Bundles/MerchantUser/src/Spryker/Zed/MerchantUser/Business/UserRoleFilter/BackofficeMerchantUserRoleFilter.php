<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\UserRoleFilter;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class BackofficeMerchantUserRoleFilter implements BackofficeMerchantUserRoleFilterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\MerchantUserConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var array<\Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserRoleFilterPreConditionPluginInterface>
     */
    protected $merchantUserRoleFilterPreConditionPlugins;

    /**
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $config
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param array<\Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserRoleFilterPreConditionPluginInterface> $merchantUserRoleFilterPreConditionPlugins
     */
    public function __construct(
        MerchantUserConfig $config,
        MerchantUserRepositoryInterface $merchantUserRepository,
        array $merchantUserRoleFilterPreConditionPlugins
    ) {
        $this->config = $config;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserRoleFilterPreConditionPlugins = $merchantUserRoleFilterPreConditionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<string> $roles
     *
     * @return array<string>
     */
    public function filterUserRoles(UserTransfer $userTransfer, array $roles): array
    {
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setIdUser(
            $userTransfer->getIdUser(),
        );

        if (!$this->merchantUserRepository->findOne($merchantUserCriteriaTransfer)) {
            return $roles;
        }

        $filteredRoles = $roles;

        foreach ($roles as $role) {
            if (in_array($role, $this->config->getBackofficeUserAuthRoles()) && $this->arePreconditionsMet($userTransfer, $role)) {
                $filteredRoles = $this->filterArray($filteredRoles, $role);
            }
        }

        return $filteredRoles;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $role
     *
     * @return bool
     */
    protected function arePreconditionsMet(UserTransfer $userTransfer, string $role): bool
    {
        foreach ($this->merchantUserRoleFilterPreConditionPlugins as $preConditionPlugin) {
            if (!$preConditionPlugin->checkCondition($userTransfer, $role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $array
     * @param string $filteredValue
     *
     * @return array
     */
    protected function filterArray(array $array, string $filteredValue): array
    {
        $result = [];

        foreach ($array as $value) {
            if ($value !== $filteredValue) {
                $result[] = $value;
            }
        }

        return $result;
    }
}
