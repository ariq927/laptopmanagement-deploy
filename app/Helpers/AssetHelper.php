<?php

if (!function_exists('manifest_asset')) {
    function manifest_asset($key)
    {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifestPath = public_path('build/.vite/manifest.json');
            $manifest = file_exists($manifestPath) 
                ? json_decode(file_get_contents($manifestPath), true) 
                : [];
        }
        
        // Cari berdasarkan key langsung atau berdasarkan src
        if (isset($manifest[$key]['file'])) {
            return asset('build/' . $manifest[$key]['file']);
        }
        
        // Fallback: cari berdasarkan src
        foreach ($manifest as $item) {
            if (isset($item['src']) && $item['src'] === $key) {
                return asset('build/' . $item['file']);
            }
        }
        
        // Jika tidak ditemukan, return path default
        return asset('build/' . $key);
    }
}