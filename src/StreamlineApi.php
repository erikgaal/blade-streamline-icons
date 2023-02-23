<?php

namespace ErikGaal\BladeStreamlineIcons;

use ErikGaal\BladeStreamlineIcons\Exceptions\IconNotPurchasedException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class StreamlineApi
{
    public function __construct(
        private readonly StreamlineCredentials $auth,
        private readonly string $baseUrl = 'https://api.streamlinehq.com',
    ) {
    }

    private function buildRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => $this->auth->getToken(),
        ])->baseUrl($this->baseUrl);
    }

    public function search(string $family, string $query): Collection
    {
        return $this->buildRequest()
            ->get('/v2/search', [
                'family' => $family,
                'query' => $query,
            ])
            ->throw()
            ->collect('data.icons.Search Results');
    }

    public function download(string $iconHash): string
    {
        $response = $this->buildRequest()
            ->get("/v3/icons/{$iconHash}/download", [
                'format' => 'SVG',
                'size' => 48,
                'colors' => '"#000000":"currentColor"',
                'outlined' => false,
                'jsx' => false,
                'action' => 'copy',
            ]);

        if ($response->forbidden()) {
            throw new IconNotPurchasedException($response->json('error'));
        }

        return $response->body();
    }
}
