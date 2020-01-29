<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OauthAccessTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthAccessToken;
use Propel\Runtime\Collection\Collection;

class OauthAccessTokenMapper
{
    /**
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthAccessToken $oauthAccessTokenEntity
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    public function mapOauthAccessTokenEntityToOauthAccessTokenDataTransfer(
        SpyOauthAccessToken $oauthAccessTokenEntity,
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
    ): OauthAccessTokenDataTransfer {
        $oauthAccessTokenDataTransfer->fromArray($oauthAccessTokenEntity->toArray(), true);
        $oauthAccessTokenDataTransfer->setOauthClientId($oauthAccessTokenEntity->getFkOauthClient());
        $oauthAccessTokenDataTransfer->setOauthAccessTokenId((string)$oauthAccessTokenEntity->getIdOauthAccessToken());
        $oauthAccessTokenDataTransfer->setOauthUserId($oauthAccessTokenEntity->getUserIdentifier());

        return $oauthAccessTokenDataTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $accessTokenEntities
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenCollectionTransfer
     */
    public function mapOauthAccessTokenEntityCollectionToOauthAccessTokenTransferCollection(
        Collection $accessTokenEntities
    ): OauthAccessTokenCollectionTransfer {
        $oauthAccessTokenCollectionTransfer = new OauthAccessTokenCollectionTransfer();

        foreach ($accessTokenEntities as $accessTokenEntity) {
            $accessTokenTransfer = $this->mapOauthAccessTokenEntityToOauthAccessTokenDataTransfer(
                $accessTokenEntity,
                new OauthAccessTokenDataTransfer()
            );
            $oauthAccessTokenCollectionTransfer->addOauthAccessTokenData($accessTokenTransfer);
        }

        return $oauthAccessTokenCollectionTransfer;
    }
}
