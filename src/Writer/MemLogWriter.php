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

namespace Wedeto\Log\Writer;

use Wedeto\Log\Logger;
use Wedeto\Log\Formatter\PatternFormatter;

/**
 * Log all received log entries to memory.
 */
class MemLogWriter extends AbstractWriter
{
    /** The last constructed instance */
    protected static $instance = null;

    /** The log storage */
    protected $log = array();

    /**
     * Create the logwriter
     * @param string $level The minimum level of messages to store
     */
    public function __construct(string $level)
    {
        $this->min_level = Logger::getLevelNumeric($level);
        $this->setFormatter(new PatternFormatter("%LEVEL%: %MESSAGE%"));
        self::$instance = $this;
    }

    /**
     * Log a line to the memory log, if its level is high enough
     * @param string $level The level of the message
     * @param string $message The message
     * @param array $context The variables to fill in the message
     */
    public function write(string $level, string $message, array $context)
    {
        $levnum = Logger::getLevelNumeric($level);
        if ($levnum < $this->min_level)
            return;

        $lvl = sprintf("%10s", $level);
        $message = $this->format($lvl, $message, $context);
        $this->log[] = $message; //sprintf("%10s: %s", strtoupper($level), $message);
    }

    /**
     * Return the collected log lines
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Remove all og entries from the logger
     * @return MemLogWriter Provides fluent interface
     */
    public function clear()
    {
        $this->log = [];
    }

    /**
     * Get a MemLogWriter instance, if available
     */
    public static function getInstance()
    {
        return self::$instance;
    }
}
