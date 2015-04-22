<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Validator;

interface UrlUniqueValidatorInterface
{
    /**
     * @param $url
     * @throws \Exception
     */
    public function validate($url);

    /**
     * @param string $url
     */
    public function addUrl($url);
}
