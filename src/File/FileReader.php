<?php

namespace App\File;

class FileReader
{

    public function readFile(string $fileName): iterable
    {
        if (!file_exists($fileName)) {
            throw new \Exception('No file');
        }


        $f = fopen($fileName, 'r');
        try {
            while ($line = fgets($f)) {
                yield $line;
            }
        } finally {
            fclose($f);
        }

    }
}