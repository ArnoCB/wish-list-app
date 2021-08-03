<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Services\QueensProblemSolver;

class QueensProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $fen_solutions = (new QueensProblemSolver())->solveQueensProblem();
        return view('queens_solution.queens_solution', compact('fen_solutions'));
    }
}
