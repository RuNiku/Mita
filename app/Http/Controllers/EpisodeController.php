<?php

namespace App\Http\Controllers;

use App\Episode;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Symfony\Component\Console\Input\Input;
use Validator;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $episodes = DB::table('episodes');

        // Find record by 'search' get paramter
        if ($request->has('search')) {
            $episodes->where('name', 'like', '%' . $request->get('search') . '%');
        }

        $episodes = $episodes
            ->orderByDesc('created_at')
            ->paginate(15);

        if ($request->ajax()) {
            return response()->json($episodes);
        } else {
            return view('storage.index', [
                'episodes' => $episodes
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('storage.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'season' => 'required|numeric',
            'episode' => 'required|numeric'
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return $validator
                    ->errors()
                    ->toJson();
            }

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $episode = Auth::user()
                ->episodes()
                ->create([
                    'name' => $request->input('name'),
                    'season' => $request->input('season'),
                    'episode' => $request->input('episode')
                ]);

            if ($request->ajax()) {
                return response()->json($episode);
            } else {
                return redirect()
                    ->route('storage.index')
                    ->with('message', 'Successfuly created.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Auth::user()
            ->episodes()
            ->find($id);

        return view('storage.show', [
            'id' => $id,
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Auth::user()
            ->episodes()
            ->find($id);

        return view('storage.edit', [
            'id' => $id,
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'season' => 'numeric',
            'episode' => 'numeric'
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return $validator
                    ->errors()
                    ->toJson();
            } else {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        } else {
            $update = [];
            if ($request->has('name')) {
                $update['name'] = $request->get('name');
            }
            if ($request->has('season')) {
                $update['season'] = $request->get('season');
            }
            if ($request->has('episode')) {
                $update['episode'] = $request->get('episode');
            }

            $episode = Auth::user()
                ->episodes()
                ->find($id)
                ->update($update);

            if ($request->ajax()) {
                return response()->json($episode);
            } else {
                $request
                    ->session()
                    ->flash('message', 'Successfully updated.');

                return redirect()
                    ->back()
                    ->with('message', 'Successfuly updated.');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $result = Auth::user()
            ->episodes()
            ->find($id)
            ->forceDelete();

        if ($request->ajax()) {
            return response()->json($result);
        } else {
            return redirect()
                ->route('storage.index')
                ->with('message', 'Successfuly updated.');
        }
    }
}
