<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extension;

use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\TwigFilter;

class EnvironmentCoreExtension implements EnvironmentCoreExtensionInterface
{
    /**
     * @var array
     */
    protected const SYSTEM_FUNCTIONS = [
        'exec',
        'shell_exec',
        'system',
        'passthru',
        'popen',
        'proc_open',
        'eval',
        'assert',
        'create_function',
        'preg_replace', // с /e modifier
        'include',
        'include_once',
        'require',
        'require_once',
        'file_get_contents',
        'file_put_contents',
        'fopen',
        'fwrite',
        'fread',
        'unlink',
        'chmod',
        'chown',
        'curl_exec',
        'curl_multi_exec',
        'phpinfo',
        'base64_decode',
        'base64_encode',
        'mail',
        'header',
        'set_include_path',
        'ini_set',
        'dl',
        'putenv',
        'apache_setenv',
    ];

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig): Environment
    {
        foreach ($this->getFilters() as $filter) {
            $twig->addFilter($filter);
        }

        return $twig;
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     *
     * @return \CallbackFilterIterator|array
     */
    public function filter(Environment $env, $array, $arrow)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        if (method_exists(CoreExtension::class, 'filter')) {
            return CoreExtension::filter($env, $array, $arrow);
        }

        return twig_array_filter($env, $array, $arrow);
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     *
     * @return array
     */
    public function find(Environment $env, $array, $arrow)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        return CoreExtension::find($env, $array, $arrow);
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     *
     * @return array
     */
    public function map(Environment $env, $array, $arrow)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        if (method_exists(CoreExtension::class, 'map')) {
            return CoreExtension::map($env, $array, $arrow);
        }

        return twig_array_map($env, $array, $arrow);
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     * @param mixed|null $initial
     *
     * @return mixed|null
     */
    public function reduce(Environment $env, $array, $arrow, $initial = null)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        if (method_exists(CoreExtension::class, 'reduce')) {
            return CoreExtension::reduce($env, $array, $arrow, $initial);
        }

        return twig_array_reduce($env, $array, $arrow, $initial);
    }

    /**
     * @param \Closure|null $arrow
     *
     * @return bool
     */
    protected function isDisallowedPhpFunction($arrow): bool
    {
        return in_array($arrow, static::SYSTEM_FUNCTIONS);
    }

    /**
     * @return array<\Twig\TwigFilter>
     */
    protected function getFilters(): array
    {
        $filters = [
            new TwigFilter('filter', [$this, 'filter'], ['needs_environment' => true]),
            new TwigFilter('map', [$this, 'map'], ['needs_environment' => true]),
            new TwigFilter('reduce', [$this, 'reduce'], ['needs_environment' => true]),
        ];

        if (method_exists(CoreExtension::class, 'find')) {
            $filters[] = new TwigFilter('find', [$this, 'find'], ['needs_environment' => true]);
        }

        return $filters;
    }
}
