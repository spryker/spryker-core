<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Business\Repositories;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use LogicException;
use Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowEntityManagerInterface;
use Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @var \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowRepositoryInterface
     */
    protected OauthCodeFlowRepositoryInterface $oauthCodeFlowRepository;

    /**
     * @var \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowEntityManagerInterface
     */
    protected OauthCodeFlowEntityManagerInterface $oauthCodeFlowEntityManager;

    /**
     * @param \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowRepositoryInterface $oauthCodeFlowRepository
     * @param \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowEntityManagerInterface $oauthCodeFlowEntityManager
     */
    public function __construct(
        OauthCodeFlowRepositoryInterface $oauthCodeFlowRepository,
        OauthCodeFlowEntityManagerInterface $oauthCodeFlowEntityManager
    ) {
        $this->oauthCodeFlowRepository = $oauthCodeFlowRepository;
        $this->oauthCodeFlowEntityManager = $oauthCodeFlowEntityManager;
    }

    /**
     * @throws \LogicException
     *
     * @return \League\OAuth2\Server\Entities\AuthCodeEntityInterface
     */
    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        throw new LogicException('This grant does not use this method');
    }

    /**
     * @param \League\OAuth2\Server\Entities\AuthCodeEntityInterface $authCodeEntity
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        throw new LogicException('This grant does not use this method');
    }

    /**
     * @param string $codeId
     *
     * @return void
     */
    public function revokeAuthCode($codeId): void
    {
        $this->oauthCodeFlowEntityManager->deleteAuthCodeByIdentifier($codeId);
    }

    /**
     * @param string $codeId
     *
     * @return bool
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        return $this->oauthCodeFlowRepository->findAuthCodeByIdentifier($codeId) === null;
    }
}
