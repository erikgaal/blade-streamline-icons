<?php

namespace ErikGaal\BladeStreamlineIcons\Support;

class JWT
{
    private object $payload;

    public function __construct(
        public readonly string $token,
    ) {
        [, $payloadb64] = explode('.', $this->token);
        $payloadRaw = self::urlsafeB64Decode($payloadb64);
        $this->payload = (object) self::jsonDecode($payloadRaw);
    }

    public function isExpired(): bool
    {
        return isset($this->payload->exp) && time() >= $this->payload->exp;
    }

    public static function urlsafeB64Decode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function jsonDecode(string $input)
    {
        return json_decode($input, false, 512, JSON_BIGINT_AS_STRING);
    }
}
