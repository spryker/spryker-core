<?php

namespace Spryker\Client\AuthRestApi\Dependency;

use Generated\Shared\Transfer\CreateAccessTokenPreCheckResultTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Client\AuthRestApiExtension\Dependency\Plugin\CreateAccessTokenPreCheckPluginInterface;
use Spryker\Client\Redis\RedisClient;

class CustomerLoginAttemptBlockerPreCheckPlugin implements CreateAccessTokenPreCheckPluginInterface
{
    protected const THRESHOLD = 100;

    public function preCheck(OauthRequestTransfer $oauthRequestTransfer, CreateAccessTokenPreCheckResultTransfer $result): CreateAccessTokenPreCheckResultTransfer
    {
        $redisKey = $oauthRequestTransfer->getAuthContext()->getIp();

        $value = (new RedisClient())->get("user_blocking", $redisKey);
        (new RedisClient())->set("user_blocking", $redisKey, $value + 1);

        return $result->setIsSuccess($value >= static::THRESHOLD + 1);
    }
}
