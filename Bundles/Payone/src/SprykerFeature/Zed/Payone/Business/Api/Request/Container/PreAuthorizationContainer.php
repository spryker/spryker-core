<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;


class PreAuthorizationContainer extends AuthorizationContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_PREAUTHORIZATION;

}
