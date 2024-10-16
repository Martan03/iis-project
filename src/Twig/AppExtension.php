<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_route_active', [$this, 'isRouteActive']),
        ];
    }

    public function isRouteActive(string $route): string
    {
        $current = $this
            ->requestStack
            ->getCurrentRequest()
            ->attributes
            ->get('_route');
        return $current === $route ? 'active' : '';
    }
}
