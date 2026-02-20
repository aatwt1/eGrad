<?php

namespace App\Policies;

use App\Models\ReportedIssue;
use App\Models\User;

class ReportedIssuePolicy
{
    /**
     * Admin može izvoditi sve akcije - ovo se izvršava prije svih drugih provjera
     */
    public function before(User $user, string $ability): bool|null
    {
        // Ako je korisnik admin, dozvoli sve 
        if ($user->isAdmin()) {
            return true;
        }
        
        // Ako nije admin, nastavi sa normalnim provjerama
        return null; 
    }


    /**
     * Određuje da li korisnik može vidjeti prijave - korisnik može vidjeti samo svoje prijave
     */
    public function view(User $user, ReportedIssue $reportedIssue): bool
    {
        
        return $user->id === $reportedIssue->user_id;
    }

    /**
     * Određuje da li korisnik može uređivati problem
     */
    public function update(User $user, ReportedIssue $reportedIssue): bool
    {
        return $user->id === $reportedIssue->user_id;
    }

    /**
     * Određuje da li korisnik može brisati problem
     */
    public function delete(User $user, ReportedIssue $reportedIssue): bool
    {
        return $user->id === $reportedIssue->user_id;
    }
}