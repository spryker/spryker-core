<?php
/**
 * Created by PhpStorm.
 * User: devromans
 * Date: 2019-01-28
 * Time: 18:53
 */

namespace Spryker\Service\Translator\Translator;


use Symfony\Component\Translation\Loader\LoaderInterface;

interface TranslatorResourceAwareInterface
{
    /**
     * @param string $format
     * @param LoaderInterface $loader
     */
    public function addLoader($format, LoaderInterface $loader);

    /**
     * @param string $format
     * @param mixed $resource
     * @param string $locale
     * @param string $domain
     */
    public function addResource($format, $resource, $locale, $domain = null);
}
