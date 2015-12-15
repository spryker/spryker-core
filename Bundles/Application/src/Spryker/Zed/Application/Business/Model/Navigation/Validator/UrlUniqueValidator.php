<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Validator;

class UrlUniqueValidator implements UrlUniqueValidatorInterface
{

    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @param string $url
     *
     * @throws \Exception
     *
     * @return void
     */
    public function validate($url)
    {
        if (in_array($url, $this->urls)) {
            throw new UrlUniqueException($url);
        }
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function addUrl($url)
    {
        $this->urls[] = $url;
    }

}
