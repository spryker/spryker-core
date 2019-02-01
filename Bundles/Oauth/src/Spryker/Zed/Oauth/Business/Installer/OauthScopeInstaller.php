<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Installer;

use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Oauth\Business\Model\OauthScopeReaderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthScopeWriterInterface;
use Spryker\Zed\Oauth\OauthConfig;

class OauthScopeInstaller implements OauthScopeInstallerInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthScopeReaderInterface
     */
    protected $oauthScopeReader;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthScopeWriterInterface
     */
    protected $oauthScopeWriter;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\OauthScopeReaderInterface $oauthScopeReader
     * @param \Spryker\Zed\Oauth\Business\Model\OauthScopeWriterInterface $oauthScopeWriter
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        OauthScopeReaderInterface $oauthScopeReader,
        OauthScopeWriterInterface $oauthScopeWriter,
        OauthConfig $oauthConfig
    ) {
        $this->oauthScopeReader = $oauthScopeReader;
        $this->oauthScopeWriter = $oauthScopeWriter;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $scopes = $this->oauthConfig->getScopes();
        $oauthScopesTransfers = $this->getScopesByIdentifiers($scopes);

        foreach ($scopes as $scope) {
            if (!$this->isExistOauthScope($scope, $oauthScopesTransfers)) {
                $oauthScopeTransfer = (new OauthScopeTransfer())
                    ->setIdentifier($scope);

                $oauthScopesTransfers[$scope] = $this->oauthScopeWriter->save($oauthScopeTransfer);
            }
        }
    }

    /**
     * @param string[] $scopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransfers
     */
    protected function getScopesByIdentifiers(array $scopes): array
    {
        $oauthScopesTransfersWithIdentifierKeys = [];
        $oauthScopesTransfers = $this->oauthScopeReader->getScopesByIdentifiers($scopes);

        foreach ($oauthScopesTransfers as $oauthScopeTransfer) {
            $oauthScopesIdentifier = $oauthScopeTransfer->getIdentifier();
            $oauthScopesTransfersWithIdentifierKeys[$oauthScopesIdentifier] = $oauthScopeTransfer;
        }

        return $oauthScopesTransfersWithIdentifierKeys;
    }

    /**
     * @param string $oauthScopeIdentifier
     * @param \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransfers
     *
     * @return bool
     */
    protected function isExistOauthScope(string $oauthScopeIdentifier, array $oauthScopeTransfers): bool
    {
        if (isset($oauthScopeTransfers[$oauthScopeIdentifier])) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return bool
     */
    protected function isExistOauthClient(OauthClientTransfer $oauthClientTransfer): bool
    {
        return $this->oauthFacade->findClientByIdentifier($oauthClientTransfer) !== null;
    }
}
