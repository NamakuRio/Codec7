<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use Illuminate\Http\Request;

class IconController extends Controller
{
    public function select2(Request $request)
    {
        $search = $request->data['search'];

        $icons = Icon::where('icon', 'like', '%' . $search . '%')->paginate(25);

        return response()->json($icons);
    }
}
