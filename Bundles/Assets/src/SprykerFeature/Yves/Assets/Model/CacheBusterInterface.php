<?php
namespace SprykerFeature\Yves\Assets\Model;

interface CacheBusterInterface
{
    /**
     * @param string $url
     *
     * @return string
     */
    public function addCacheBust($url);
}