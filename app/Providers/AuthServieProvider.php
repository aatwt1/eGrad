<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
protected $policies = [
    ReportedIssue::class => ReportedIssuePolicy::class,
];

}