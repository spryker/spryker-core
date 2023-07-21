<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;

class OauthClientWriter implements OauthClientWriterInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface
     */
    protected OauthClientReaderInterface $oauthClientReader;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface $oauthClientReader
     */
    public function __construct(
        OauthEntityManagerInterface $oauthEntityManager,
        OauthClientReaderInterface $oauthClientReader
    ) {
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthClientReader = $oauthClientReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return \Generated\Shared\Transfer\OauthClientTransfer
     */
    public function save(OauthClientTransfer $oauthClientTransfer): OauthClientTransfer
    {
        $oauthClientEntityTransfer = new SpyOauthClientEntityTransfer();
        $oauthClientEntityTransfer->fromArray($oauthClientTransfer->toArray());

        $existingOauthClient = $this->oauthClientReader->findClientByIdentifier($oauthClientTransfer);
        if ($existingOauthClient !== null) {
            $oauthClientTransfer->setIdOauthClient($existingOauthClient->getIdOauthClientOrFail());
            $oauthClientTransfer->setSecret($oauthClientEntityTransfer->getSecretOrFail());

            return $this->oauthEntityManager->updateClient($oauthClientTransfer);
        }

        $oauthClientEntityTransfer = $this->oauthEntityManager->saveClient($oauthClientEntityTransfer);

        $oauthClientTransfer->fromArray($oauthClientEntityTransfer->toArray(), true);

        return $oauthClientTransfer;
    }
}
