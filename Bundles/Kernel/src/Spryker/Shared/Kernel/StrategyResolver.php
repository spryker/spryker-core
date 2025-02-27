<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use InvalidArgumentException;

/**
 * Specification:
 * - The strategy resolver enables the usage of different strategies based on a context selector.
 * - The strategies must be of the same type (`S`). The type is defined by the template.
 *
 * Example:
 * ```
 * $resolver = new StrategyResolver<ActualStrategyInterface>([ 'context1' => new ActualStrategy1(), 'context2' => fn() => new ActualStrategy2() ]);
 * $strategy = $resolver->get('context2'); // returns $ActualStrategy2 : ActualStrategyInterface
 * ```
 *
 * @template S
 * @implements \Spryker\Shared\Kernel\StrategyResolverInterface<S>
 */
class StrategyResolver implements StrategyResolverInterface
{
    /**
     * Specification:
     * - The configuration array has the contexts as keys and the corresponding strategy as value.
     * - The configuration array values may be callables that return the actual strategy.
     * - The configuration array values must be of type S or callable that returns S.
     *
     * Examples:
     * ```
     * $strategyConfiguration1 = [ 'context1' => [ new Strategy1() ], 'context2' => fn() => [ new Strategy2() ] ];
     * $strategyConfiguration2 = [ 'context1' => new Strategy1(), 'context2' => fn() => new Strategy2() ];
     * ```
     *
     * @var array<string, callable():S | S>
     */
    protected array $configuration;

    /**
     * Specification:
     * - The fallback context is used if the searched context is not found in the configuration.
     *
     * @var string|null
     */
    protected ?string $fallbackContext;

    /**
     * Specification:
     * - The configuration array has the contexts as keys and the corresponding strategy as value.
     * - The configuration array values may be callables that return the actual strategy.
     * - The configuration array values must be of type S or callable that returns S.
     *
     * Examples:
     * ```
     * $strategyConfiguration1 = [ 'context1' => [ new Strategy1() ], 'context2' => fn() => [ new Strategy2() ] ];
     * $strategyConfiguration2 = [ 'context1' => new Strategy1(), 'context2' => fn() => new Strategy2() ];
     * ```
     *
     * @param array<string, callable():S | S> $strategyConfiguration
     * @param string|null $fallbackContext The resolution uses the fallback context if the searched context is not found in the configuration.
     */
    public function __construct(array $strategyConfiguration, ?string $fallbackContext = null)
    {
        $this->configuration = $strategyConfiguration;
        $this->fallbackContext = $fallbackContext;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $contextSelector
     *
     * @throws \InvalidArgumentException
     *
     * @return S
     */
    public function get(?string $contextSelector)
    {
        $strategy = $this->configuration[$contextSelector]
            ?? $this->configuration[$this->fallbackContext]
            ?? throw new InvalidArgumentException("Neither context selector '$contextSelector' nor fallback context '$this->fallbackContext' was found in configuration.");

        if (is_callable($strategy)) {
            $strategy = $strategy();
        }

        return $strategy;
    }
}
