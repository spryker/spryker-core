// phpcs:ignoreFile
/**
 * @return bool
 */
protected function isSegmentQuery(): bool
{
    $segmentTableTemplate = sprintf(
        \Spryker\Service\AclEntity\SegmentConnectorGenerator\SegmentConnectorGenerator::CONNECTOR_CLASS_TEMPLATE,
        \Spryker\Service\AclEntity\SegmentConnectorGenerator\SegmentConnectorGenerator::ENTITY_PREFIX_DEFAULT,
        ''
    );

    return strpos($this->getModelShortName(), $segmentTableTemplate) === 0;
}
