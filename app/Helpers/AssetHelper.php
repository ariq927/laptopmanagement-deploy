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
        
        if (isset($manifest[$key]['file'])) {
            return asset('build/' . $manifest[$key]['file']);
        }
        
        foreach ($manifest as $item) {
            if (isset($item['src']) && $item['src'] === $key) {
                return asset('build/' . $item['file']);
            }
        }
        
        return asset('build/' . $key);
    }
}