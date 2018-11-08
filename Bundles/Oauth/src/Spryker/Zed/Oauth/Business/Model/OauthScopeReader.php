<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthScopeReader implements OauthScopeReaderInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $repository
     */
    public function __construct(OauthRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer|null
     */
    public function findScopeByIdentifier(OauthScopeTransfer $oauthScopeTransfer): ?OauthScopeTransfer
    {
        $oauthScopeTransfer->requireIdentifier();

        $oauthScopeIdentifier = $oauthScopeTransfer->getIdentifier();

        $entityTransfer = $this->repository->findScopeByIdentifier($oauthScopeIdentifier);

        if (!$entityTransfer) {
            return null;
        }

        return $oauthScopeTransfer->fromArray($entityTransfer->toArray(), true);
    }

    /**
     * @param string[] $customerScopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopesByIdentifiers(array $customerScopes): array
    {
        return $this->repository->getScopesByIdentifiers($customerScopes);
    }
}
