<?php

namespace App\Support;

class View
{
    public static function render(string $title, string $body, array $options = []): void
    {
        $variant = htmlspecialchars($options['variant'] ?? 'A', ENT_QUOTES, 'UTF-8');
        $query = htmlspecialchars($options['query'] ?? '', ENT_QUOTES, 'UTF-8');
        $titleEscaped = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        echo '<!doctype html><html lang="en"><head>';
        echo '<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<title>' . $titleEscaped . '</title>';
        echo '<link rel="stylesheet" href="/assets/app.css">';
        echo '</head><body data-variant="' . $variant . '" data-query="' . $query . '">';
        echo '<header class="topbar"><a href="/vsl' . ($query ? '?' . $query : '') . '" class="brand">FunnelCase</a>';
        echo '<nav><a href="/qa">QA</a><a href="/admin">Admin</a><a href="/members">Members</a></nav></header>';
        echo '<main>' . $body . '</main>';
        echo '<script src="/assets/app.js" defer></script>';
        echo '</body></html>';
    }
}

