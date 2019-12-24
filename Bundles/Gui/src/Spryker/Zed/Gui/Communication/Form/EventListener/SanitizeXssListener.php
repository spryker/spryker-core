<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\EventListener;

use Spryker\Zed\Gui\Communication\Form\Type\Extension\SanitizeXssTypeExtension;
use Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SanitizeXssListener implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceInterface
     */
    protected $utilSanitizeXssService;

    /**
     * @param \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceInterface $utilSanitizeService
     */
    public function __construct(GuiToUtilSanitizeXssServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeXssService = $utilSanitizeService;
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

        $formConfig = $event->getForm()->getConfig();
        $data = $this->utilSanitizeXssService->sanitizeXss(
            $data,
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_ATTRIBUTES, []),
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_HTML_TAGS, [])
        );

        $event->setData($this->utilSanitizeXssService->sanitizeXss(
            $data,
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_ATTRIBUTES, []),
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_HTML_TAGS, [])
        ));
    }
}
