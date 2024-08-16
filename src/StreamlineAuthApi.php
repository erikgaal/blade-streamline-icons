<?php

namespace ErikGaal\BladeStreamlineIcons;

use ErikGaal\BladeStreamlineIcons\Support\JWT;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class StreamlineAuthApi
{
    public function __construct(
        public readonly string $baseUrl = 'https://identitytoolkit.googleapis.com/',
        private readonly string $key = 'AIzaSyCHKw0Ss271_0_7bpBfOL_M-K4fCn5omM0',
    ) {}

    private function buildRequest(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl);
    }

    public function signInWithPassword(string $email, string $password): StreamlineCredentials
    {
        $response = $this->buildRequest()
            ->post("/v1/accounts:signInWithPassword?key={$this->key}", [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ])->throw();

        return new StreamlineCredentials(
            token: new JWT($response->json('idToken')),
            refreshToken: $response->json('refreshToken'),
        );
    }

    public function account(StreamlineCredentials $credentials): object
    {
        $response = $this->buildRequest()
            ->post("/v1/accounts:lookup?key={$this->key}", [
                'idToken' => $credentials->getToken(),
            ])->throw();

        return (object) $response->json('users.0');
    }

    public function token(StreamlineCredentials $credentials): StreamlineCredentials
    {
        $response = $this->buildRequest()
            ->post("/v1/token?key={$this->key}", [
                'grant_type' => 'refresh_token',
                'refresh_token' => $credentials->getRefreshToken(),
            ])->throw();

        return new StreamlineCredentials(
            token: new JWT($response->json('id_token')),
            refreshToken: $response->json('refresh_token'),
        );
    }
}
