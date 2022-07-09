<?php

require_once model_path('Type');
require_once model_path('Recipe');

class TypesController extends Controller
{
    public function handle($url = null)
    {
        if (is_null($url))
            return true;

        $type = Type::findByUrl($url);

        if (empty($type))
            return false;

        return true;
    }

    public function index()
    {
        $types = Type::get();
        return view('types.index', ['types' => $types]);
    }

    public function show($url)
    {
        $type = Type::findByUrl($url);
        return view('types.show', ['type' => $type]);
    }
}
