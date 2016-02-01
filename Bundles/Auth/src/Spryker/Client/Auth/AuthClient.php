<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method AuthFactory getFactory()
 */
class AuthClient extends AbstractClient implements AuthClientInterface
{

    /**
     * @param string $rawToken
     *
     * @return string
     */
    public function generateToken($rawToken)
    {
        return $this->getTokenService()->generate($rawToken);
    }

    /**
     * @param string $rawToken
     * @param string $hash
     *
     * @return bool
     */
    public function checkToken($rawToken, $hash)
    {
        return $this->getTokenService()->check($rawToken, $hash);
    }

    /**
     * @return \Spryker\Client\Auth\Token\TokenService
     */
    private function getTokenService()
    {
        return $this->getFactory()->createTokenService();
    }

}
