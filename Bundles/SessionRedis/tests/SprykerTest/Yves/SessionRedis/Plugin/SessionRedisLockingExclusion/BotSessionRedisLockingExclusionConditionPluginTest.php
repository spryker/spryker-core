<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Unit\Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion\BotSessionRedisLockingExclusionConditionPlugin;
use Spryker\Yves\SessionRedis\SessionRedisDependencyProvider;
use SprykerTest\Yves\SessionRedis\SessionRedisYvesTester;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group SessionRedis
 * @group Plugin
 * @group SessionRedisLockingExclusion
 * @group BotSessionRedisLockingExclusionConditionPluginTest
 * Add your own group annotations below this line
 */
class BotSessionRedisLockingExclusionConditionPluginTest extends Unit
{
    /**
     * @var \Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion\BotSessionRedisLockingExclusionConditionPlugin
     */
    protected BotSessionRedisLockingExclusionConditionPlugin $plugin;

    /**
     * @var \SprykerTest\Yves\SessionRedis\SessionRedisYvesTester
     */
    protected SessionRedisYvesTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SessionRedisDependencyProvider::REQUEST_STACK, new RequestStack());

        $configMock = $this->tester->mockConfigMethod('getSessionRedisLockingExcludedBotUserAgents', [
            'Googlebot',
            'bingbot',
            'Baiduspider',
            'YandexBot',
            'DuckDuckBot',
            'Sogou',
            'ia_archiver',
            'facebookexternalhit',
            'Twitterbot',
            'LinkedInBot',
            'Slackbot',
            'WhatsApp',
            'Discordbot',
            'AhrefsBot',
            'Applebot',
            'msnbot',
            'MJ12bot',
            'SEMrushBot',
            'PetalBot',
            'SeznamBot',
            'AdsBot-Google',
            'crawler',
            'spider',
            'robot',
            'bot/',
        ]);

        $factory = $this->tester->getFactory();
        $factory->setConfig($configMock);

        $this->plugin = new BotSessionRedisLockingExclusionConditionPlugin();
        $this->plugin->setFactory($factory);
    }

    /**
     * @dataProvider botUserAgentDataProvider
     *
     * @param string $userAgent
     *
     * @return void
     */
    public function testCheckConditionReturnsTrueForBotUserAgents(string $userAgent): void
    {
        // Arrange
        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestHeaders(['User-Agent' => $userAgent]);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertTrue($result, "Failed asserting that user agent '$userAgent' is recognized as a bot");
    }

    /**
     * @dataProvider botUserAgentDataProvider
     *
     * @param string $userAgent
     *
     * @return void
     */
    public function testCheckConditionHandlesLowercaseUserAgentHeaderKey(string $userAgent): void
    {
        // Arrange
        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestHeaders(['user-agent' => $userAgent]);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @dataProvider regularUserAgentDataProvider
     *
     * @param string $userAgent
     *
     * @return void
     */
    public function testCheckConditionReturnsFalseForRegularUserAgents(string $userAgent): void
    {
        // Arrange
        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestHeaders(['User-Agent' => $userAgent]);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result, "Failed asserting that user agent '$userAgent' is recognized as a regular user");
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFalseWhenHeadersAreNotProvided(): void
    {
        // Arrange
        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFalseWhenUserAgentIsEmpty(): void
    {
        // Arrange
        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestHeaders(['User-Agent' => '']);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFalseWhenUserAgentHeaderIsMissing(): void
    {
        // Arrange
        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestHeaders(['Some-Other-Header' => 'value']);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return array<array<string>>
     */
    public function botUserAgentDataProvider(): array
    {
        return [
            ['Googlebot/2.1'],
            ['Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.59 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'],
            ['Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'],
            ['Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)'],
            ['Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)'],
            ['Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)'],
            ['DuckDuckBot/1.0; (+http://duckduckgo.com/duckduckbot.html)'],
            ['Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)'],
            ['facebookexternalhit/1.1'],
            ['Mozilla/5.0 (compatible; Twitterbot/1.0)'],
            ['LinkedInBot/1.0 (compatible; Mozilla/5.0)'],
            ['Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)'],
            ['WhatsApp/2.21.12.21 A'],
            ['Mozilla/5.0 (compatible; Discordbot/2.0)'],
            ['Mozilla/5.0 (compatible; AhrefsBot/7.0)'],
            ['Mozilla/5.0 (compatible; Applebot/0.1)'],
            ['msnbot/2.0b (+http://search.msn.com/msnbot.htm)'],
            ['Mozilla/5.0 (compatible; MJ12bot/v1.4.8)'],
            ['Mozilla/5.0 (compatible; SEMrushBot/7.0)'],
            ['Mozilla/5.0 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)'],
            ['SeznamBot/3.2'],
            ['AdsBot-Google (+http://www.google.com/adsbot.html)'],
            ['Some Generic Crawler v1.0'],
            ['Generic Spider Bot/1.0'],
            ['TestRobot/1.0'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function regularUserAgentDataProvider(): array
    {
        return [
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15'],
            ['Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1'],
            ['Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36'],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0'],
        ];
    }
}
