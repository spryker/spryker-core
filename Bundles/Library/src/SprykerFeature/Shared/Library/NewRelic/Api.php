<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\NewRelic;

/**
 * The PHP API for New Relic
 *
 * @link https://newrelic.com/docs/php/the-php-api
 */
class Api implements ApiInterface
{

    /**
     * @var
     */
    protected static $instance;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var string
     */
    protected $nameOfTransaction;

    /**
     * Protected Singleton-Constructor. Use \SprykerFeature\Shared\Library\NewRelic\Api::getInstance() instead.
     */
    protected function __construct()
    {
        $this->active = extension_loaded('newrelic');
    }

    /**
     * @static
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * Report an error at this line of code, with a complete stack trace.
     *
     * @param $message
     * @param Exception $e
     *
     * @return $this
     */
    public function noticeError($message, \Exception $e)
    {
        if ($this->active) {
            newrelic_notice_error($message, $e);
        }

        return $this;
    }

    /**
     * Sets the name of the application to string. The string uses the same format as newrelic.appname and can set
     * multiple application names by separating each with a semi-colon. The first application name is the primary name,
     * and up to two extra application names can be specified. This function should be called as early as possible, and
     * will have no effect if called after the RUM footer has been sent. You may want to consider setting the
     * application name in a file loaded by PHP's auto_prepend_file INI setting. This function returns true if it
     * succeeded or false otherwise.
     *
     * @param $name
     *
     * @return $this
     */
    public function setAppName($name)
    {
        if ($this->active) {
            newrelic_set_appname($name);
        }

        return $this;
    }

    /**
     * Sets the name of the transaction to the specified string. This can be useful if you have implemented your own
     * dispatching scheme and wish to name transactions according to their purpose rather than their URL.
     *
     * Keep in mind that you want to make sure that you do not create too many unique transaction names.
     * For example, if you have /product/123 and /product/234, if you generate a separate transaction name for each,
     * then New Relic will store separate information for these two transaction names. This will make your graphs less
     * useful, and may run into limits we set on the number of unique transaction names per account. It also can slow
     * down the performance of your application. Instead, store the transaction as /product/*, or use something
     * significant about the code itself to name the transaction, such as /Product/view. The limit for the total number
     * of transactions should be less than 1000 unique transaction names -- exceeding that is not recommended.
     *
     * @param $name
     *
     * @return $this
     */
    public function setNameOfTransaction($name)
    {
        $this->nameOfTransaction = $name;

        if ($this->active) {
            newrelic_name_transaction($name);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getNameOfTransaction()
    {
        return $this->nameOfTransaction;
    }

    /**
     * Stop recording the web transaction immediately. Usually used when a page is done with all computation and is
     * about to stream data (file download, audio or video streaming etc) and you don't want the time taken to stream to
     * be counted as part of the transaction. This is especially relevant when the time taken to complete the operation
     * is completely outside the bounds of your application. For example, a user on a very slow connection may take a
     * very long time to download even small files, and you wouldn't want that download time to skew the real
     * transaction time.
     *
     * @return $this
     */
    public function markEndOfTransaction()
    {
        if ($this->active) {
            newrelic_end_of_transaction();
        }

        return $this;
    }

    /**
     * Do not generate metrics for this transaction. This is useful when you have transactions that are particularly
     * slow for known reasons and you do not want them always being reported as the transaction trace or skewing your
     * site averages.
     *
     * @return $this
     */
    public function markIgnoreTransaction()
    {
        if ($this->active) {
            newrelic_ignore_transaction();
        }

        return $this;
    }

    /**
     * Do not generate Apdex metrics for this transaction. This is useful when you have either very short or very long
     * transactions (such as file downloads) that can skew your apdex score.
     *
     * @return $this
     */
    public function markIgnoreApdex()
    {
        if ($this->active) {
            newrelic_ignore_apdex();
        }

        return $this;
    }

    /**
     * If no argument or true as an argument is given, mark the current transaction as a background job. If false is
     * passed as an argument, mark the transaction as a web application.
     *
     * @param $flag
     *
     * @return $this
     */
    public function markAsBackgroundJob($flag = true)
    {
        assert(is_bool($flag));
        if ($this->active) {
            newrelic_background_job($flag);
        }

        return $this;
    }

    /**
     * Adds a custom metric with the specified name and value, which is of type double. These custom metrics can then
     * be used in custom views in the New Relic User Interface.
     *
     * @param $metricName
     * @param $value
     *
     * @return $this
     */
    public function addCustomMetric($metricName, $value)
    {
        if ($this->active) {
            if(strpos($metricName, 'Custom/') !== 0) {
                $metricName = 'Custom/' . $metricName;
            }
            newrelic_custom_metric($metricName, $value);
        }

        return $this;
    }

    /**
     * Add a custom parameter to the current web transaction with the specified value. For example, you can add a
     * customer's full name from your customer database.
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addCustomParameter($key, $value)
    {
        if ($this->active) {
            newrelic_add_custom_parameter($key, $value);
        }

        return $this;
    }

    /**
     * API equivalent of the newrelic.transaction_tracer.customi setting. It allows you to add functions or methods to
     * the list to be instrumented.
     *
     * @param string $tracer
     *
     * @return $this
     */
    public function addCustomTracer($tracer = 'classname::function_name')
    {
        if ($this->active) {
            newrelic_add_custom_tracer($tracer);
        }

        return $this;
    }

    /**
     * Returns the JavaScript string to inject as part of the header for browser timing (real user monitoring). If flag
     * is specified it must be a boolean, and if omitted, defaults to true. This indicates whether or not surrounding
     * script tags should be returned as part of the string.
     *
     * @param bool $flag
     *
     * @return $this
     */
    public function getBrowserTimingHeader($flag = true)
    {
        if ($this->active) {
            newrelic_get_browser_timing_header($flag);
        }

        return $this;
    }

    /**
     * Returns the JavaScript string to inject at the very end of the HTML output for browser timing (real user
     * monitoring). If flag is specified it must be a boolean, and if omitted, defaults to true. This indicates whether
     * or not surrounding script tags should be returned as part of the string.
     *
     * @param bool $flag
     *
     * @return $this
     */
    public function getBrowserTimingFooter($flag = true)
    {
        if ($this->active) {
            newrelic_get_browser_timing_footer($flag);
        }

        return $this;
    }

    /**
     * Prevents the output filter from attempting to insert RUM JAvaScript for this current transaction. Useful for
     * AJAX calls, for example.
     *
     * @return $this
     */
    public function disableAutoRUM()
    {
        if ($this->active) {
            newrelic_disable_autorum();
        }

        return $this;
    }

}
