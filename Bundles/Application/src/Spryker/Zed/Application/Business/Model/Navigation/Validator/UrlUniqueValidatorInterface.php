<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Validator;

interface UrlUniqueValidatorInterface
{

    /**
     * @param string $url
     *
     * @throws \Exception
     */
    public function validate($url);

    /**
     * @param string $url
     */
    public function addUrl($url);

}
