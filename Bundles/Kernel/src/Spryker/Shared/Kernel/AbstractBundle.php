<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use Spryker\Shared\Config\Config;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated Will be removed with next major release
 */
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
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            self::OPTION_KEY_APPLICATION => self::APPLICATION,
            self::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => self::BUNDLE_PATH_PATTERN,
            self::OPTION_KEY_BUNDLE_PATH_PATTERN => self::BUNDLE_PATH_PATTERN,
            self::OPTION_KEY_PROJECT_PATH_PATTERN => APPLICATION_SOURCE_DIR,
            self::OPTION_KEY_VENDOR_PATH_PATTERN => Config::get(KernelConstants::SPRYKER_ROOT) . '/*/src',
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
