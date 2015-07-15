<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractBundle
{

    const OPTION_KEY_PROJECT_PATH_PATTERN = 'project path pattern';
    const OPTION_KEY_VENDOR_PATH_PATTERN = 'core path pattern';
    const OPTION_KEY_APPLICATION = 'application';
    const OPTION_KEY_BUNDLE_PATH_PATTERN = 'bundle path pattern';
    const OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN = 'bundle project path pattern';

    const APPLICATION = '*';
    const BUNDLE_PATH_PATTERN = '*/';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            self::OPTION_KEY_APPLICATION => self::APPLICATION,
            self::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => self::BUNDLE_PATH_PATTERN,
            self::OPTION_KEY_BUNDLE_PATH_PATTERN => self::BUNDLE_PATH_PATTERN,
            self::OPTION_KEY_PROJECT_PATH_PATTERN => APPLICATION_SOURCE_DIR,
            self::OPTION_KEY_VENDOR_PATH_PATTERN => APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src',
        ]);

        $resolver->setRequired([
            self::OPTION_KEY_PROJECT_PATH_PATTERN,
            self::OPTION_KEY_VENDOR_PATH_PATTERN,
            self::OPTION_KEY_APPLICATION,
        ]);

        $resolver->setAllowedTypes(self::OPTION_KEY_PROJECT_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_VENDOR_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_APPLICATION, 'string');
    }

}
