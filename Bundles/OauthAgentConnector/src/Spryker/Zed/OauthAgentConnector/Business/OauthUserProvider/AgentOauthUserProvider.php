<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\OauthUserProvider;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\OauthAgentConnector\Business\Adapter\PasswordEncoderAdapterInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface;

class AgentOauthUserProvider implements AgentOauthUserProviderInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const COL_STATUS_ACTIVE = 'active';

    /**
     * @var \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface
     */
    protected $agentFacade;

    /**
     * @var \Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\OauthAgentConnector\Business\Adapter\PasswordEncoderAdapterInterface
     */
    protected $passwordEncoderAdapter;

    /**
     * @param \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface $agentFacade
     * @param \Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthAgentConnector\Business\Adapter\PasswordEncoderAdapterInterface $passwordEncoderAdapter
     */
    public function __construct(
        OauthAgentConnectorToAgentFacadeInterface $agentFacade,
        OauthAgentConnectorToUtilEncodingServiceInterface $utilEncodingService,
        PasswordEncoderAdapterInterface $passwordEncoderAdapter
    ) {
        $this->agentFacade = $agentFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->passwordEncoderAdapter = $passwordEncoderAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getAgentOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $oauthUserTransfer->setIsSuccess(false);

        $userTransfer = $this->findActiveAgentByUsername($oauthUserTransfer->getUsername());
        if (!$userTransfer) {
            return $oauthUserTransfer;
        }

        $isAuthorized = $this->passwordEncoderAdapter->isPasswordValid(
            $userTransfer->getPassword(),
            $oauthUserTransfer->getPassword(),
            null,
        );

        if (!$isAuthorized) {
            return $oauthUserTransfer;
        }

        $customerIdentifierTransfer = (new CustomerIdentifierTransfer())
            ->setIdAgent($userTransfer->getIdUser());

        $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($customerIdentifierTransfer->toArray()))
            ->setIsSuccess(true);

        return $oauthUserTransfer;
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findActiveAgentByUsername(string $username): ?UserTransfer
    {
        $findAgentResponseTransfer = $this->agentFacade->findAgentByUsername($username);
        $userTransfer = $findAgentResponseTransfer->getAgent();

        if (!$findAgentResponseTransfer->getIsAgentFound()) {
            return null;
        }

        if ($userTransfer && $userTransfer->getStatus() === static::COL_STATUS_ACTIVE) {
            return $userTransfer;
        }

        return null;
    }
}
