<?php

namespace App\Helpers;

use App\Entity\House;
use Illuminate\Support\Facades\Storage;

class ImportHouses
{
    const SOURCE_FILE_PATH = 'source/property-data.csv';

    private $content;

    public function makeImport()
    {
        $this->getFileContent();
        $this->prepareLoadedData();
        $this->createDbRows();
    }

    private function getFileContent()
    {
        if (Storage::missing(self::SOURCE_FILE_PATH)) {
            throw new \Exception('Source file is not found');
        }

        $this->content = Storage::disk('local')->get(self::SOURCE_FILE_PATH);
        if (!$this->content) {
            throw new \Exception('Source file get content error');
        }
    }

    private function prepareLoadedData()
    {
        $dataContentCollection = collect(explode("\r\n", $this->content));
        $dataContentCollection->shift(); // remove header
        if (!$dataContentCollection->count()) {
            throw new \Exception('Empty source data collection');
        }

        $this->content = $dataContentCollection->map(function ($row) {
            list($name, $price, $bedrooms, $bathrooms, $storeys, $garages) = explode(',', $row);
            return [
                'name' => $name,
                'price' => (int)$price,
                'bedrooms' => (int)$bedrooms,
                'bathrooms' => (int)$bathrooms,
                'storeys' => (int)$storeys,
                'garages' => (int)$garages,
            ];
        });
    }

    private function createDbRows()
    {
        foreach($this->content as $preparedRow) {
            House::create($preparedRow);
        }
    }
}
