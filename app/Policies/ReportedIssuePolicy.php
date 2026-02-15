<?php

namespace App\Policies;

use App\Models\ReportedIssue;
use App\Models\User;

class ReportedIssuePolicy
{
    
    public function view(User $user, ReportedIssue $reportedIssue): bool
    {
        // Korisnik može vidjeti samo svoje probleme
        return $user->id === $reportedIssue->user_id;
    }

    public function update(User $user, ReportedIssue $reportedIssue): bool
    {
        return $user->id === $reportedIssue->user_id;
    }


    public function delete(User $user, ReportedIssue $reportedIssue): bool
    {
        return $user->id === $reportedIssue->user_id;
    }
}