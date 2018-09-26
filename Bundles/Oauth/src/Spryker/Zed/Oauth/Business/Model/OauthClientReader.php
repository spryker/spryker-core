<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use Generated\Shared\Transfer\OauthClientTransfer;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthClientReader implements OauthClientReaderInterface
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
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return \Generated\Shared\Transfer\OauthClientTransfer|null
     */
    public function findClientByIdentifier(OauthClientTransfer $oauthClientTransfer): ?OauthClientTransfer
    {
        $oauthClientTransfer->requireIdentifier();

        $oauthClientIdentifier = $oauthClientTransfer->getIdentifier();

        $entityTransfer = $this->repository->findClientByIdentifier($oauthClientIdentifier);

        if (!$entityTransfer) {
            return null;
        }

        return $oauthClientTransfer->fromArray($entityTransfer->toArray(), true);
    }
}
