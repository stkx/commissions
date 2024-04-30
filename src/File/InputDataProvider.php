<?php

namespace App\File;


class InputDataProvider
{


    public function __construct(private readonly FileReader $fileReader)
    {
    }

    public function readFile(string $fileName): iterable
    {
        $values = [];

        foreach ($this->fileReader->readFile($fileName) as $line) {

            if (empty($line)) {
                continue;
            }

            try {
                $values[] = json_decode(json: $line, associative: true, flags: JSON_THROW_ON_ERROR);
            } catch (\Throwable) {
                continue;
            }

        }

        return $values;
    }
}