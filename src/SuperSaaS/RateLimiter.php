<?php namespace SuperSaaS;

class RateLimiter
{
    private static int $_windowSize = 1; // seconds
    private static int $_maxPerWindow = 4;
    private static array $_queue = [];

    /**
     * @return void
     */
    public static function throttle(): void
    {
        // Ensure the queue is initialized
        if (count(self::$_queue) < self::$_maxPerWindow) {
            self::$_queue = array_fill(0, self::$_maxPerWindow, null);
        }

        // Represents the timestamp of the oldest request within the time window
        $oldestRequest = array_shift(self::$_queue);
        array_push(self::$_queue, microtime(true));

        if ($oldestRequest !== null) {
            $d = microtime(true) - $oldestRequest;
            if ($d < self::$_windowSize) {
                // Calculate the time to sleep to enforce rate limiting
                $sleepTime = (self::$_windowSize - $d) * 1000000; // Convert to microseconds
                usleep($sleepTime);
            }
        }
    }
}
