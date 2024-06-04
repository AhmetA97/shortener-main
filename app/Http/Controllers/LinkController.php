<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{

    // Show the index page
    public function index()
    {
        $links = Link::latest()->take(10)->get();

        return view('pages.index', compact('links'));
    }

    // Shorten the URL
    public function shorten(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $link = new Link;
        $link->shorten($request->url);

        return response()->json(['code' => $link->code]);
    }

    // Redirect to the original URL
    public function redirect($code)
    {
        $link = Link::where('code', $code)->firstOrFail();

        return redirect($link->url);
    }

    // Get the latest links
    public function getLatestLinks()
    {
        $links = Link::latest()->take(10)->get();

        return response()->json($links);
    }
}
