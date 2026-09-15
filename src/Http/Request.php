<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final readonly class Request
{
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     * @param array<string, mixed> $files
     * @param array<string, string> $server
     */
    public function __construct(
        public string $method,
        public string $path,
        public array $query = [],
        public array $post = [],
        public array $files = [],
        public array $server = [],
    ) {
    }

    public static function fromGlobals(): self
    {
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $path = rawurldecode((string) (parse_url($uri, PHP_URL_PATH) ?: '/'));
        return new self($method, $path, $_GET, $_POST, $_FILES, self::stringServer($_SERVER));
    }

    /**
     * @param array<string, mixed> $server
     * @return array<string, string>
     */
    private static function stringServer(array $server): array
    {
        $result = [];
        foreach ($server as $key => $value) {
            if (is_scalar($value)) {
                $result[$key] = (string) $value;
            }
        }
        return $result;
    }
}
