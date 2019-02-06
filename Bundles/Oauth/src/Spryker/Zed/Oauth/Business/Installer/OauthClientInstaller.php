<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Installer;

use Generated\Shared\Transfer\OauthClientTransfer;
use Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface;
use Spryker\Zed\Oauth\OauthConfig;

class OauthClientInstaller implements OauthClientInstallerInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface
     */
    protected $oauthClientReader;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface
     */
    protected $oauthClientWriter;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface $oauthClientReader
     * @param \Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface $oauthClientWriter
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        OauthClientReaderInterface $oauthClientReader,
        OauthClientWriterInterface $oauthClientWriter,
        OauthConfig $oauthConfig
    ) {
        $this->oauthClientReader = $oauthClientReader;
        $this->oauthClientWriter = $oauthClientWriter;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $clients = $this->oauthConfig->getClients();
        foreach ($clients as $idClient => $client) {
            foreach ($client as $clientName => $clientSecret) {
                $oauthClientTransfer = new OauthClientTransfer();
                $oauthClientTransfer->setIdentifier($idClient);

                if (!$this->hasOauthClient($oauthClientTransfer)) {
                    $oauthClientTransfer->setSecret(
                        password_hash($clientSecret, PASSWORD_BCRYPT)
                    );
                    $oauthClientTransfer->setIsConfidential(true);
                    $oauthClientTransfer->setName($clientName);

                    $this->oauthClientWriter->save($oauthClientTransfer);
                }
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return bool
     */
    protected function hasOauthClient(OauthClientTransfer $oauthClientTransfer): bool
    {
        return $this->oauthClientReader->findClientByIdentifier($oauthClientTransfer) !== null;
    }
}
