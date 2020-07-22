<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\OauthUserProvider;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;

class AgentProvider implements AgentProviderInterface
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
     * @param \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface $agentFacade
     * @param \Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthAgentConnectorToAgentFacadeInterface $agentFacade,
        OauthAgentConnectorToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->agentFacade = $agentFacade;
        $this->utilEncodingService = $utilEncodingService;
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

        // Inject this.
        $encoder = new NativePasswordEncoder();
        $isAuthorized = $encoder->isPasswordValid(
            $findAgentResponseTransfer->getAgent()->getPassword(),
            $oauthUserTransfer->getPassword(),
            null
        );
        if (!$isAuthorized) {
            return $oauthUserTransfer;
        }

        $customerIdentifierTransfer = (new CustomerIdentifierTransfer())
            ->setAgentUserName($findAgentResponseTransfer->getAgent()->getUsername())
            ->setIdAgent($findAgentResponseTransfer->getAgent()->getIdUser());

        $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($customerIdentifierTransfer->toArray()))
            ->setIsSuccess(true);

        return $oauthUserTransfer;
    }
}
