<?php
/**
 * Created by PhpStorm.
 * User: devromans
 * Date: 2019-01-28
 * Time: 18:59
 */

namespace Spryker\Service\Translator\TranslatorBuilder;


use Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface;
use Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface;

class TranslatorBuilder implements TranslatorBuilderInterface
{
    /**
     * @var array|\Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface[]
     */
    protected $translationResourceFileLoaders;

    /**
     * TranslatorBuilder constructor.
     *
     * @param \Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface[] $translationResourceFileLoaders
     */
    public function __construct(array $translationResourceFileLoaders = [])
    {
        $this->translationResourceFileLoaders = $translationResourceFileLoaders;
    }

    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface $translator
     *
     * @return \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface
     */
    public function buildTranslator(TranslatorResourceAwareInterface $translator): TranslatorResourceAwareInterface
    {
        $translator = $this->initializeResources($translator);

        return $translator;
    }

    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface $translator
     *
     * @return \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface
     */
    protected function initializeResources(TranslatorResourceAwareInterface $translator): TranslatorResourceAwareInterface
    {
        foreach ($this->translationResourceFileLoaders as $translationResourceFileLoader) {
            $loaderFormat = $translationResourceFileLoader->getLoader()->getFormat();
            $translator->addLoader($loaderFormat, $translationResourceFileLoader->getLoader());

            $this->addResources($translator, $translationResourceFileLoader, $loaderFormat);
        }

        return $translator;
    }

    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface $translator
     * @param \Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface $translationResourceFileLoader
     * @param string $loaderFormat
     *
     * @return \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface
     */
    protected function addResources(
        TranslatorResourceAwareInterface $translator,
        TranslationResourceFileLoaderInterface $translationResourceFileLoader,
        string $loaderFormat
    ): TranslatorResourceAwareInterface
    {
        foreach ($translationResourceFileLoader->getFilePaths() as $filePath) {
            $translationResourceLocale = $translationResourceFileLoader->findLocaleFromFilename($filePath);
            if (!$translationResourceLocale) {
                continue;
            }

            $translator->addResource($loaderFormat, $filePath, $translationResourceLocale, $translationResourceFileLoader->getDomain());
        }

        return $translator;
    }
}
