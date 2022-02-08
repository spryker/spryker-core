<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Communication\Twig;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\CmsBlock\Communication\Exception\MissingCmsBlockException;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToStoreFacadeInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface;
use Twig\Environment;

class RenderCmsBlockAsTwigFunctionProvider extends TwigFunctionProvider
{
    /**
     * @var string
     */
    protected const FUNCTION_NAME = 'renderCmsBlockAsTwig';

    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface
     */
    protected $cmsBlockRepository;

    /**
     * @var \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface $cmsBlockRepository
     * @param \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CmsBlockRepositoryInterface $cmsBlockRepository,
        CmsBlockToStoreFacadeInterface $storeFacade
    ) {
        $this->cmsBlockRepository = $cmsBlockRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Twig\Environment $environment
     * @param array<mixed> $context
     * @param string $cmsBlockName
     * @param string|null $storeName
     * @param string $localeName
     * @param array<mixed>|null $providedData
     *
     * @throws \Spryker\Zed\CmsBlock\Communication\Exception\MissingCmsBlockException
     *
     * @return string
     */
    public function getCmsBlockTwig(
        Environment $environment,
        array $context,
        string $cmsBlockName,
        ?string $storeName,
        string $localeName,
        ?array $providedData = null
    ): string {
        if ($storeName === null) {
            $storeName = $this->storeFacade->getCurrentStore()->getNameOrFail();
        }

        $cmsBlockTransfer = $this->cmsBlockRepository
            ->findCmsBlockWithGlossary($cmsBlockName, $storeName, $localeName);

        if (!$cmsBlockTransfer) {
            throw new MissingCmsBlockException(sprintf(
                'Could no find a CMS block for CMS block name "%s", store "%s" and locale "%s"',
                $cmsBlockName,
                $storeName,
                $localeName,
            ));
        }

        $templateContext = $providedData ?: $context;

        $placeholdersContent = $this->getPlaceholdersContent(
            $environment,
            $cmsBlockTransfer->getGlossary(),
            $templateContext,
        );

        $templateContext['placeholders'] = $placeholdersContent;

        return $environment->render(
            $cmsBlockTransfer->getCmsBlockTemplate()->getTemplatePath(),
            $templateContext,
        );
    }

    /**
     * @param \Twig\Environment $environment
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     * @param array<mixed> $templateContext
     *
     * @return array<string>
     */
    protected function getPlaceholdersContent(
        Environment $environment,
        CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer,
        array $templateContext
    ): array {
        $placeholdersContent = [];

        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $glossaryPlaceholderTransfer) {
            /** @var \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer $translationTransfer */
            $translationTransfer = $glossaryPlaceholderTransfer->getTranslations()
                ->offsetGet(0);
            $placeholderTemplate = $translationTransfer->getTranslation();
            $placeholdersContent[$glossaryPlaceholderTransfer->getPlaceholder()] = $environment
                ->createTemplate($placeholderTemplate)
                ->render($templateContext);
        }

        return $placeholdersContent;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return callable|array
     */
    public function getFunction()
    {
        return [$this, 'getCmsBlockTwig'];
    }

    /**
     * @return array<mixed>
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        $options['needs_environment'] = true;
        $options['needs_context'] = true;

        return $options;
    }
}
