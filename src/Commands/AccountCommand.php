<?php

namespace ErikGaal\BladeStreamlineIcons\Commands;

use ErikGaal\BladeStreamlineIcons\BladeStreamlineIcons;
use ErikGaal\BladeStreamlineIcons\StreamlineAuthApi;
use ErikGaal\BladeStreamlineIcons\StreamlineCredentials;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AccountCommand extends Command
{
    public $signature = 'streamline-icons:account';

    public $description = '';

    public function handle(StreamlineAuthApi $authApi, StreamlineCredentials $credentials): int
    {
        try {
            $account = $authApi->account($credentials);
        } catch (Exception $e) {
            $this->error('You are not logged in to Streamline Icons. Please login first with the `streamline-icon:account` command.');
            return self::FAILURE;
        }

        $this->info('You are logged in as ' . $account->email . '.');

        return self::SUCCESS;
    }
}
