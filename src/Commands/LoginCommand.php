<?php

namespace ErikGaal\BladeStreamlineIcons\Commands;

use ErikGaal\BladeStreamlineIcons\StreamlineAuthApi;
use Illuminate\Console\Command;

class LoginCommand extends Command
{
    public $signature = 'streamline-icons:login {email?}';

    public $description = 'Login to Streamline Icons';

    public function handle(StreamlineAuthApi $authApi): int
    {
        $this->line('Please enter your Streamline credentials to continue.');

        $email = $this->argument('email') ?? $this->ask('Email');
        $password = $this->secret('Password');

        $credentials = $authApi->signInWithPassword($email, $password);

        $this->info('Successfully logged in to Streamline Icons!');

        $credentials->saveToFile();

        return self::SUCCESS;
    }
}
