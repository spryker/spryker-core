<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

class DateFormatter
{
    const DATE_FORMAT_SHORT = 'short';
    const DATE_FORMAT_MEDIUM = 'medium';
    const DATE_FORMAT_RFC = 'rfc';
    const DATE_FORMAT_DATETIME = 'datetime';

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws \Exception
     *
     * @return string
     */
    public function dateShort($date, $context = null, $convertTz = true)
    {
        return $this->formatDate($date, self::DATE_FORMAT_SHORT, $context, $convertTz);
    }

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws \Exception
     *
     * @return string
     */
    public function dateMedium($date, $context = null, $convertTz = true)
    {
        return $this->formatDate($date, self::DATE_FORMAT_MEDIUM, $context, $convertTz);
    }

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws \Exception
     *
     * @return string
     */
    public function dateRFC($date, $context = null, $convertTz = true)
    {
        return $this->formatDate($date, self::DATE_FORMAT_RFC, $context, $convertTz);
    }

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws \Exception
     *
     * @return string
     */
    public function dateTime($date, $context = null, $convertTz = true)
    {
        return $this->formatDate($date, self::DATE_FORMAT_DATETIME, $context, $convertTz);
    }

    /**
     * @param \DateTime|string $date
     * @param string $dateFormat
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function formatDate($date, $dateFormat, $context = null, $convertTz = true)
    {
        $context = $this->getContext($context);

        if (!isset($context->dateFormat[$dateFormat])) {
            throw new \Exception('Unsupported date format: ' . $dateFormat);
        }

        if ($convertTz) {
            return $context->dateTimeConvertTo($date, $context->dateFormat[$dateFormat]);
        }

        if (!($date instanceof \DateTime)) {
            $date = new \DateTime($date);
        }

        return $date->format($context->dateFormat[$dateFormat]);
    }

    /**
     * @param \SprykerFeature_Shared_Library_Context|string $context
     *
     * @throws \Exception
     *
     * @return \SprykerFeature_Shared_Library_Context
     */
    protected function getContext($context)
    {
        return \SprykerFeature_Shared_Library_Context::getInstance($context);
    }

}
