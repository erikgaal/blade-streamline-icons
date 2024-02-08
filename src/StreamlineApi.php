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

    public function search(IconFamily $family, string $query): Collection
    {
        return $this->buildRequest()
            ->get('/v4/search', [
                'family' => $family->name,
                'query' => $query,
            ])
            ->throw()
            ->collect('results')
            ->pluck('categories.*.subcategories.*.icons')
            ->flatten(2);
    }

    public function download(string $iconHash): string
    {
        $response = $this->buildRequest()
            ->get("/v4/icons/{$iconHash}/download", [
                'format' => 'SVG',
                'size' => 48,
                'colors' => '"#000000":"currentColor"',
                'responsive' => 'true',
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
