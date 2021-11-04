<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\OauthUserProvider;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthAgentConnector\Business\Adapter\PasswordEncoderAdapterInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface;

class AgentOauthUserProvider implements AgentOauthUserProviderInterface
{
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

        $findAgentResponseTransfer = $this->agentFacade->findAgentByUsername($oauthUserTransfer->getUsername());
        if (!$findAgentResponseTransfer->getIsAgentFound()) {
            return $oauthUserTransfer;
        }

        $isAuthorized = $this->passwordEncoderAdapter->isPasswordValid(
            $findAgentResponseTransfer->getAgent()->getPassword(),
            $oauthUserTransfer->getPassword(),
            null,
        );

        if (!$isAuthorized) {
            return $oauthUserTransfer;
        }

        $customerIdentifierTransfer = (new CustomerIdentifierTransfer())
            ->setIdAgent($findAgentResponseTransfer->getAgent()->getIdUser());

        $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($customerIdentifierTransfer->toArray()))
            ->setIsSuccess(true);

        return $oauthUserTransfer;
    }
}
