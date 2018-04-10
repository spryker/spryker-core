<?php

namespace Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;

interface QuoteExpanderPluginInterface
{
    /**
     * Communication layer plugins which allow to start manual order entry with an existing quote
     *
     * Specification:
     * - Uses request to define an initial state of a quote
     * - If a plugin is not able to init a quote it must return NULL
     *
     * @param Request $request
     *
     * @return QuoteTransfer|null
     */
    public function expand(QuoteTransfer $quoteTransfer, Request $request): ?QuoteTransfer;
}