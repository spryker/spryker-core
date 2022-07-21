<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Dependency\External;

use Symfony\Component\Yaml\Yaml;

class OauthToYamlAdapter implements OauthToYamlInterface
{
    /**
     * @param mixed $input
     * @param int $inline
     * @param int $indent
     * @param int $flags
     *
     * @return string
     */
    public function dump(
        $input,
        int $inline = self::YAML_DEFAULT_INLINE,
        int $indent = self::YAML_DEFAULT_INDENT,
        int $flags = self::YAML_DEFAULT_FLAG
    ): string {
        return Yaml::dump($input, $inline, $indent, $flags);
    }
}
