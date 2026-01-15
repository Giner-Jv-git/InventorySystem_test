<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Get initials from a name
     */
    public static function getInitials(string $name): string
    {
        $parts = explode(' ', trim($name));
        $initials = '';
        
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]);
            }
        }
        
        return substr($initials, 0, 2) ?: '?';
    }

    /**
     * Get avatar URL or generate initials avatar
     */
    public static function getAvatarUrl(?string $photoPath, string $name): string
    {
        if ($photoPath && file_exists(storage_path('app/public/' . $photoPath))) {
            return asset('storage/' . $photoPath);
        }
        
        return 'data:image/svg+xml,' . self::generateInitialsAvatar(
            self::getInitials($name),
            self::getColorForName($name)
        );
    }

    /**
     * Generate inline SVG avatar with initials
     */
    public static function generateInitialsAvatar(string $initials, string $bgColor): string
    {
        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                <rect width="40" height="40" fill="%s" rx="8"/>
                <text x="20" y="26" font-size="16" font-weight="bold" fill="white" text-anchor="middle" font-family="Arial, sans-serif">%s</text>
            </svg>',
            $bgColor,
            htmlspecialchars($initials)
        );
        
        return urlencode($svg);
    }

    /**
     * Get a consistent color based on name
     */
    public static function getColorForName(string $name): string
    {
        $colors = [
            '#E89B7A', // coral
            '#7CB9C8', // teal
            '#F5A962', // orange
            '#A8D5BA', // mint
            '#FFB6C1', // light pink
            '#87CEEB', // sky blue
            '#F0E68C', // khaki
            '#DDA0DD', // plum
        ];
        
        $hash = abs(crc32($name));
        return $colors[$hash % count($colors)];
    }

    /**
     * Format file size for display
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= 1 << (10 * $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
