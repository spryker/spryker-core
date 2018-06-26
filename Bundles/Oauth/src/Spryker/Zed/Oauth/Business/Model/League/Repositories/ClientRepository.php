<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\ClientEntity;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     */
    public function __construct(OauthRepositoryInterface $oauthRepository)
    {
        $this->oauthRepository = $oauthRepository;
    }

    /**
     * @param string $clientIdentifier The client's identifier
     * @param null|string $grantType The grant type used (if sent)
     * @param null|string $clientSecret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $oauthClientEntityTransfer = $this->oauthRepository->findClientByIdentifier($clientIdentifier);
        $clientEntity = new ClientEntity();

        if (!$oauthClientEntityTransfer) {
            return;
        }

        if ($mustValidateSecret === true
            && $oauthClientEntityTransfer->getIsConfidental() === true
            && password_verify($clientSecret, $oauthClientEntityTransfer->getSecret()) === false
        ) {
            return;
        }

        $clientEntity->setIdentifier($oauthClientEntityTransfer->getIdentifier());
        $clientEntity->setName($oauthClientEntityTransfer->getName());
        $clientEntity->setRedirectUri($oauthClientEntityTransfer->getRedirectUri());

        return $clientEntity;
    }
}
