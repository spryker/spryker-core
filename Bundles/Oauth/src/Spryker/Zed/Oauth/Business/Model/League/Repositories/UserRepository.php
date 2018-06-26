<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use Generated\Shared\Transfer\OauthUserTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\UserEntity;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface[]
     */
    protected $userProviderPlugins;

    /**
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface[] $userProviderPlugins
     */
    public function __construct(array $userProviderPlugins = [])
    {
        $this->userProviderPlugins = $userProviderPlugins;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     *
     * @return \League\OAuth2\Server\Entities\UserEntityInterface
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {

        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer
            ->setIsSuccess(false)
            ->setUsername($username)
            ->setPassword($password)
            ->setClientId($clientEntity->getIdentifier())
            ->setGrantType($grantType)
            ->setClientName($clientEntity->getName());

        foreach ($this->userProviderPlugins as $userProviderPlugin) {
            if (!$userProviderPlugin->accept($oauthUserTransfer)) {
                continue;
            }
            $oauthUserTransfer = $userProviderPlugin->getUser($oauthUserTransfer);

            if (!$oauthUserTransfer->getIsSuccess()) {
                return null;
            }
        }

        if ($oauthUserTransfer->getIsSuccess() && $oauthUserTransfer->getUserIdentifier()) {
            return new UserEntity($oauthUserTransfer->getUserIdentifier());
        }
    }
}
