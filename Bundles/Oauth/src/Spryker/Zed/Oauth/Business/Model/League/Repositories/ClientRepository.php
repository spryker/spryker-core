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
     * @var array
     */
    protected static $oauthClientEntityTransferCache = [];

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     */
    public function __construct(OauthRepositoryInterface $oauthRepository)
    {
        $this->oauthRepository = $oauthRepository;
    }

    /**
     * @param string $clientIdentifier The client's identifier
     *
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface|null
     */
    public function getClientEntity($clientIdentifier)
    {
        $oauthClientEntityTransfer = $this->findOauthClientEntityTransfer($clientIdentifier);

        if (!$oauthClientEntityTransfer) {
            return null;
        }

        $clientEntity = new ClientEntity();

        $clientEntity->setIdentifier($oauthClientEntityTransfer->getIdentifier());
        $clientEntity->setName($oauthClientEntityTransfer->getName());
        $clientEntity->setRedirectUri($oauthClientEntityTransfer->getRedirectUri());

        return $clientEntity;
    }

    /**
     * @param string $clientIdentifier The client's identifier
     * @param string|null $clientSecret The client's secret (if sent)
     * @param string|null $grantType The type of grant the client is using (if sent)
     *
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $oauthClientEntityTransfer = $this->findOauthClientEntityTransfer($clientIdentifier);

        if (!$oauthClientEntityTransfer) {
            return false;
        }

        if (
            $oauthClientEntityTransfer->getIsConfidential() === true
            && password_verify($clientSecret, $oauthClientEntityTransfer->getSecret()) === false
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $clientIdentifier The client's identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthClientEntityTransfer|null
     */
    protected function findOauthClientEntityTransfer($clientIdentifier)
    {
        if (!isset(static::$oauthClientEntityTransferCache[$clientIdentifier])) {
            static::$oauthClientEntityTransferCache[$clientIdentifier] = $this->oauthRepository
                ->findClientByIdentifier($clientIdentifier);
        }

        return static::$oauthClientEntityTransferCache[$clientIdentifier];
    }
}
