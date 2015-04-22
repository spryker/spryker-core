<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Validator;

class UrlUniqueValidator implements UrlUniqueValidatorInterface
{

    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @param $url
     * @throws \Exception
     */
    public function validate($url)
    {
        if (in_array($url, $this->urls)) {
            throw new UrlUniqueException($url);
        }
    }

    /**
     * @param string $url
     */
    public function addUrl($url)
    {
        $this->urls[] = $url;
    }
}
