<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('project_role', [$this, 'translateRole'])
        ];
    }

    public function translateRole(string $role)
    {
        return $role === "MANAGER" ? "GESTIONNAIRE" : "Spectateur";
    }
}
