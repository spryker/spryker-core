<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\LifeTime;

use Generated\Shared\Transfer\HttpRequestTransfer;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionRedisLifeTimeCalculator implements SessionRedisLifeTimeCalculatorInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @var \Spryker\Zed\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface[]
     */
    protected $sessionRedisLifeTimeCalculatorPlugins;

    /**
     * @var int
     */
    protected $defaultSessionLifeTime;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Spryker\Zed\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface[] $sessionRedisLifeTimeCalculatorPlugins
     * @param int $defaultSessionLifeTime
     */
    public function __construct(
        RequestStack $requestStack,
        array $sessionRedisLifeTimeCalculatorPlugins,
        int $defaultSessionLifeTime
    ) {
        $this->requestStack = $requestStack;
        $this->sessionRedisLifeTimeCalculatorPlugins = $sessionRedisLifeTimeCalculatorPlugins;
        $this->defaultSessionLifeTime = $defaultSessionLifeTime;
    }

    /**
     * @return int
     */
    public function getSessionLifeTime(): int
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return $this->defaultSessionLifeTime;
        }

        $httpRequestTransfer = $this->createHttpRequestTransfer();
        foreach ($this->sessionRedisLifeTimeCalculatorPlugins as $sessionRedisLifeTimeCalculatorPlugin) {
            if ($sessionRedisLifeTimeCalculatorPlugin->isApplicable($httpRequestTransfer)) {
                return $sessionRedisLifeTimeCalculatorPlugin->getLifeTime($httpRequestTransfer);
            }
        }

        return $this->defaultSessionLifeTime;
    }

    /**
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    protected function createHttpRequestTransfer(): HttpRequestTransfer
    {
        $httpRequestTransfer = new HttpRequestTransfer();
        $currentRequestHeaders = $this->requestStack->getCurrentRequest()->headers;

        foreach ($currentRequestHeaders->keys() as $key) {
            $httpRequestTransfer->addHeader($key, $currentRequestHeaders->get($key));
        }

        return $httpRequestTransfer;
    }
}
