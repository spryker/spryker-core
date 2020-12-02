<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Reader;

use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface;

class ResourceOwnerReader implements ResourceOwnerReaderInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface
     */
    protected $securityOauthUserFacade;

    public function __construct(SecurityOauthUserFacadeInterface $securityOauthUserFacade)
    {
        $this->securityOauthUserFacade = $securityOauthUserFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerTransfer|null
     */
    public function getResourceOwner(Request $request): ?ResourceOwnerTransfer
    {
        $authenticationCode = $request->query->get(SecurityOauthUserConfig::REQUEST_PARAMETER_AUTHENTICATION_CODE);
        $authenticationState = $request->query->get(SecurityOauthUserConfig::REQUEST_PARAMETER_AUTHENTICATION_STATE);

        if (!$authenticationCode || !$authenticationState) {
            return null;
        }

        $resourceOwnerRequestTransfer = $this->createResourceOwnerRequestTransfer($request);
        $resourceOwnerResponseTransfer = $this->securityOauthUserFacade->getResourceOwner($resourceOwnerRequestTransfer);

        if (!$resourceOwnerResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $resourceOwnerResponseTransfer->getResourceOwner();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerRequestTransfer
     */
    protected function createResourceOwnerRequestTransfer(Request $request): ResourceOwnerRequestTransfer
    {
        return (new ResourceOwnerRequestTransfer())->fromArray($request->query->all(), true);
    }
}
