<?php

namespace System;

class View
{

    public static function render(string $path, array $data = [])
    {
        $fullPath = __DIR__ . '/../Views/' . $path . '.php';
        
        if (!file_exists($fullPath)) {
            throw new \ErrorException('view cannot be found');
        }

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }
        
        include($fullPath);
    }
}

