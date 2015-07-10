<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Auth\Service\Token\TokenService;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 */
class AuthClient extends AbstractClient implements AuthClientInterface
{

    /**
     * @param $rawToken
     *
     * @return string
     */
    public function generateToken($rawToken)
    {
        return $this->getTokenService()->generate($rawToken);
    }

    /**
     * @param $rawToken
     * @param $hash
     *
     * @return bool
     */
    public function checkToken($rawToken, $hash)
    {
        return $this->getTokenService()->check($rawToken, $hash);
    }

    /**
     * @return TokenService
     */
    private function getTokenService()
    {
        return $this->getDependencyContainer()->createTokenService();
    }

}
