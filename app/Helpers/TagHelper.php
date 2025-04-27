<?php

namespace App\Helpers;

use App\Models\User;

class TagHelper
{
    /**
     * Ekstrak username dari teks dan cari ID pengguna terkait.
     *
     * @param string $text
     * @return array Array of user IDs
     */
    public static function extractMentions($text)
    {
        // Regex untuk mencocokkan username setelah simbol @
        preg_match_all('/@(\w+)/', $text, $matches);

        // Ambil semua username yang cocok
        $usernames = $matches[1];

        // Cari ID pengguna berdasarkan username
        return User::whereIn('username', $usernames)->pluck('id')->toArray();
    }
}
