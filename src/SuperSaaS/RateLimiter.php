<?php namespace SuperSaaS;

class RateLimiter
{
    private static int $_windowSize = 1; // 1 second between requests
    private static int $_maxPerWindow = 1;
    private static ?float $_lastRequestTime = null;

    /**
     * @return void
     */
    public static function throttle(): void
    {
        $now = microtime(true);

        if (self::$_lastRequestTime !== null) {
            $timeSinceLastRequest = $now - self::$_lastRequestTime;
            if ($timeSinceLastRequest < self::$_windowSize) {
                $sleepTime = (self::$_windowSize - $timeSinceLastRequest) * 1000000;
                usleep($sleepTime);
            }
        }

        self::$_lastRequestTime = microtime(true);
    }
}
