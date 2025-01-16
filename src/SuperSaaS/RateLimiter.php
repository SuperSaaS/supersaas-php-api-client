<?php 
namespace SuperSaaS;

class RateLimiter {
    private const WINDOW_SIZE = 1;
    private const MAX_REQUESTS = 4;
    private static $queue = [];

    /**
     * @return void
     */
    public static function throttle(): void 
    {
        self::$queue[] = microtime(true);
        
        if (count(self::$queue) > self::MAX_REQUESTS) {
            array_shift(self::$queue);
        }

        if (count(self::$queue) < self::MAX_REQUESTS) {
            return;
        }

        $oldestRequest = reset(self::$queue);

        $elapsed = microtime(true) - $oldestRequest;

        if ($elapsed < self::WINDOW_SIZE) {
            $sleepTime = self::WINDOW_SIZE - $elapsed;
            usleep((int)($sleepTime * 1000000));
        }
    }
}
