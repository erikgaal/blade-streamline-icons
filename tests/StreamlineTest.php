<?php
declare(strict_types=1);

use ErikGaal\BladeStreamlineIcons\BladeStreamlineIcons;
use ErikGaal\BladeStreamlineIcons\StreamlineCredentials;
use ErikGaal\BladeStreamlineIcons\Tests\TestCase;

it('can resolve the service', function () {
    app(StreamlineCredentials::class);
    app(BladeStreamlineIcons::class);
});
