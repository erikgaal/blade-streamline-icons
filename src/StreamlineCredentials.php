<?php

namespace ErikGaal\BladeStreamlineIcons;

use ErikGaal\BladeStreamlineIcons\Support\JWT;

class StreamlineCredentials
{
    public function __construct(
        private JWT $token,
        private string $refreshToken,
    ) {
    }

    public function getToken(): string
    {
        if ($this->isExpired()) {
            $this->refresh();
        }

        return $this->token->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function isExpired(): bool
    {
        return $this->token->isExpired();
    }

    public function refresh(): void
    {
        $newCredentials = resolve(StreamlineAuthApi::class)->token($this);

        $this->token = $newCredentials->token;

        $this->saveToFile();
    }

    public static function loadFromFile(): ?self
    {
        $file = file_get_contents(base_path('.streamline-icons.json'));

        if (! $file) {
            return null;
        }

        $content = json_decode($file, associative: true);

        return new self(
            token: new JWT($content['token']),
            refreshToken: $content['refreshToken'],
        );
    }

    public function saveToFile(): void
    {
        file_put_contents(base_path('.streamline-icons.json'), json_encode([
            'token' => $this->token->token,
            'refreshToken' => $this->refreshToken,
        ]));
    }
}
