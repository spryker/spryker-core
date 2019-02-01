<?php
/**
 * Created by PhpStorm.
 * User: devromans
 * Date: 2019-01-28
 * Time: 19:09
 */

namespace Spryker\Service\Translator\TranslatorBuilder;


use Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface;

interface TranslatorBuilderInterface
{
    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface $translator
     *
     * @return \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface
     */
    public function buildTranslator(TranslatorResourceAwareInterface $translator): TranslatorResourceAwareInterface;
}
