<?php

namespace App\Services;

class BookmarkService {

    /* Check for 404 status of given url */
    public static function isUrl404(string $url): bool 
    {
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        return $httpCode == 404 ? true : false; 
    }
}