<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Movies;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel; 

class MoviesController extends Controller
{
    public function createFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');

        $data = Excel::toArray([], $file)[0];

        foreach ($data as $row) {
            if (isset($row[0])) {
                $rowData = explode(';', $row[0]);
        
                if (count($rowData) >= 5) {
                    $modelData = [
                        'year' => $rowData[0],
                        'title' => $rowData[1],
                        'studios' => $rowData[2],
                        'producers' => $rowData[3],
                        'winner' => $rowData[4] ? true: false,
                    ];
        
                    Movies::create($modelData);
                }
            }
        }
        

        return redirect()->back()->with('success', 'Data inserted successfully.');
    }

    public function create(Request $request)
    {
        $requestData = $request->json()->all();

        // Verifique se o título já existe na tabela de filmes
        $existingMovie = Movies::where('title', $requestData['title'])->first();

        if ($existingMovie) {
            return ['success' => false, 'message' => 'Movie with this title already exists.'];
        }

        $movieData = [
            'year' => $requestData['year'],
            'title' => $requestData['title'],
            'studios' => $requestData['studios'],
            'producers' => $requestData['producers'],
        ];

        if (isset($requestData['winner'])) {
            $movieData['winner'] = $requestData['winner'];
        }

        try {
            $movie = Movies::create($movieData);

            return ['success' => true, 'message' => 'Movie created successfully.', 'movie' => $movie];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create the movie.'];
        }
    }




    public function update(Request $request)
    {
        $requestData = $request->json()->all();

        // Verifique se o campo 'id' está presente na solicitação
        if (!isset($requestData['id'])) {
            return ['success' => false, 'message' => 'Movie ID is missing in the request.'];
        }

        $id = $requestData['id'];

        try {
            $movie = Movies::findOrFail($id);

            // Verifique se o ID na solicitação corresponde ao ID do filme
            if ($movie->id != $id) {
                return ['success' => false, 'message' => 'Invalid movie ID.'];
            }

            if (isset($requestData['year'])) {
                $movie->year = $requestData['year'];
            }

            if (isset($requestData['title'])) {
                // Verifique se o novo título já existe na tabela de filmes
                $existingMovie = Movies::where('title', $requestData['title'])->where('id', '<>', $id)->first();
                if ($existingMovie) {
                    return ['success' => false, 'message' => 'Movie with this title already exists.'];
                }

                $movie->title = $requestData['title'];
            }

            if (isset($requestData['studios'])) {
                $movie->studios = $requestData['studios'];
            }

            if (isset($requestData['producers'])) {
                $movie->producers = $requestData['producers'];
            }

            if (isset($requestData['winner'])) {
                $movie->winner = $requestData['winner'];
            }

            $movie->save();

            return ['success' => true, 'message' => 'Movie updated successfully.', 'movie' => $movie];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update the movie.'];
        }
    }



    

    public function list($id)
    {
        try {
            $movie = Movies::findOrFail($id);
    
            return ['success' => true, 'movie' => $movie];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Movie not found.'];
        }
    }

    public function listAll()
    {
        try {
            $movies = Movies::all();

            return ['success' => true, 'movies' => $movies];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to fetch movies.'];
        }
    }

    public function delete($id)
    {
        try {
            $movie = Movies::findOrFail($id);
            $movie->delete();
    
            return ['success' => true, 'message' => 'Movie deleted successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete the movie.'];
        }
    }
}
