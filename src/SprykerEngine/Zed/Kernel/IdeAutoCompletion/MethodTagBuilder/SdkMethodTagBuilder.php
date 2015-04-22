<?php

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SdkMethodTagBuilder extends AbstractSingleFileMethodTagBuilder
{

    const METHOD_STRING_PATTERN = '@method {{className}} sdk()';
    const APPLICATION_SDK = 'Sdk';
    const FILE_NAME_SUFFIX = 'Sdk.php';
    const PATH_PATTERN = '';

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_APPLICATION => self::APPLICATION_SDK,
            self::OPTION_KEY_METHOD_STRING_PATTERN => self::METHOD_STRING_PATTERN,
            self::OPTION_KEY_PATH_PATTERN => self::PATH_PATTERN,
            self::OPTION_KEY_FILE_NAME_SUFFIX => self::FILE_NAME_SUFFIX,
        ]);
    }

    /**
     * @param $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = [])
    {
        $methodTag = $this->getMethodTag($bundle);
        if ($methodTag) {
            $methodTags[] = $methodTag;
        }

        return $methodTags;
    }
}
