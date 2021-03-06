<?php
/*
This is part of Wedeto, the WEb DEvelopment TOolkit.
It is published under the MIT Open Source License.

Copyright 2017, Egbert van der Wal

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace Wedeto\Log;

use Psr\Log\NullLogger;
use Wedeto\Util\Hook;
use Wedeto\Util\Dictionary;

/**
 * This class is used by all loggers to obtain their logger
 */
class LoggerFactory
{
    private static $logger_factory = null;

    /**
     * Set the factory used to provide loggers
     */
    public static function setLoggerFactory(LoggerFactory $factory)
    {
        self::$logger_factory = $factory;
    }

    /** 
     * This function is subscribed to the Wedeto.Util.GetLogger hook to obtain their logger.
     */
    public static function getLogger(array $context = array())
    {
        $str = \Wedeto\Util\Functions::str($context);
        if (self::$logger_factory === null)
            return new NullLogger();

        return self::$logger_factory->get(array($context['class'] ?? "Wedeto.UndefinedLogger"));
    }

    /** 
     * This function is subscribed to the Wedeto.Util.GetLogger hook to obtain their logger.
     */
    public static function getLoggerHook(Dictionary $params)
    {
        $params['logger'] = self::getLogger($params->toArray());
    }

    /** 
     * The default implementation simply defers to the getLogger method
     * of the Wedeto\Log\Logger class
     */
    public function get(array $context = array())
    {
        $logger = Logger::getLogger($context[0]);
        return $logger;
    }
}

// @codeCoverageIgnoreStart
Hook::subscribe("Wedeto.Util.GetLogger", array(LoggerFactory::class, "getLoggerHook"));
// @codeCoverageIgnoreEnd
