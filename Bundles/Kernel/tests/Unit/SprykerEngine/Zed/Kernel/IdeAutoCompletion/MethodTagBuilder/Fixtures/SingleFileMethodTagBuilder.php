<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\Fixtures;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\AbstractSingleFileMethodTagBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SingleFileMethodTagBuilder extends AbstractSingleFileMethodTagBuilder
{

    const METHOD_STRING_PATTERN = '@method {{className}} singleFileMethod()';
    const APPLICATION = 'Application';

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_METHOD_STRING_PATTERN => self::METHOD_STRING_PATTERN,
            self::OPTION_KEY_APPLICATION => self::APPLICATION,
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
