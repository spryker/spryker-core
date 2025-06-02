<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin\Console;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \Spryker\Yves\Twig\TwigFactory getFactory()
 * @method \Spryker\Yves\Twig\TwigConfig getConfig()
 */
class TwigTemplateWarmingModeEventSubscriberPlugin extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected const FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED = 'FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED';

    /**
     * {@inheritDoc}
     * - Can only be enabled for `\Spryker\Yves\Twig\Plugin\Console\TwigTemplateWarmerConsole` command.
     * - Subscribed to the `ConsoleEvents::COMMAND` event with priority 100 to ensure it is executed right after the command is initialized.
     * - Sets the `TwigCacheWarmingModeEventSubscriberPlugin::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED` flag if the command is `TwigTemplateWarmerConsole::NAME`.
     *
     * @api
     *
     * @return array<string, array<int, int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => ['onConsoleCommand', 100],
        ];
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->getContainer()->set(
            static::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED,
            $event->getCommand()->getName() === TwigTemplateWarmerConsole::NAME,
        );
    }
}
