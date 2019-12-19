<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\EventListener;

use Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SanitizeXssListener implements EventSubscriberInterface
{
    protected const ATTRIBUTES_WHITELIST = [
        'style',
    ];

    protected const HTML_TAGS_WHITELIST = [
        'iframe',
    ];

    /**
     * @var \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(GuiToUtilSanitizeServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => ['sanitizeSubmittedData', 1000],
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function sanitizeSubmittedData(FormEvent $event): void
    {
        $data = $event->getData();

        if (!is_string($data)) {
            return;
        }

        $event->setData($this->utilSanitizeService->sanitizeXss(
            $data,
            static::ATTRIBUTES_WHITELIST,
            static::HTML_TAGS_WHITELIST
        ));
    }
}
