<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class RedirectBuilder implements RedirectBuilderInterface
{

    /**
     * @var KeyBuilderInterface
     */
    protected $redirectKeyBuilder;

    /**
     * @param KeyBuilderInterface $redirectKeyBuilder
     */
    public function __construct(KeyBuilderInterface $redirectKeyBuilder)
    {
        $this->redirectKeyBuilder = $redirectKeyBuilder;
    }

    /**
     * @param array $redirectResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, LocaleTransfer $locale)
    {
        $returnedResultSet = [];
        foreach ($redirectResultSet as $index => $redirect) {
            //TODO make this more pretty
            $this->redirectKeyBuilder->setResourceType('redirect');

            $redirectKey = $this->redirectKeyBuilder->generateKey($redirect['redirect_id'], $locale->getLocaleName());
            $returnedResultSet[$redirectKey] = [
                'from_url' => $redirect['from_url'],
                'to_url' => $redirect['to_url'],
                'status' => $redirect['status'],
                'id' => $redirect['redirect_id'],
            ];
        }

        return $returnedResultSet;
    }

}
