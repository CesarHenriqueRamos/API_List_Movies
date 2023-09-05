<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Movies;

class MoviesImport implements ToModel
{
    public function model(array $row)
    {
        return new Movies([
            'year' => $row[0],
            'title' => $row[1],
            'studios' => $row[2],
            'producers' => $row[3],
            'winner' => $row[4],
        ]);
    }
}
