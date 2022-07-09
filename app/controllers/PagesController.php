<?php

class PagesController extends Controller
{
    public function handle($data = null)
    {
        return true;
    }

    public function index()
    {
        $types = Type::getPopulars();
        $recipes = Recipe::getLatests();
        return view('pages.home', ['recipes' => $recipes, 'types' => $types]);
    }

    public function show($url)
    {
        $recipe = Recipe::findByUrl($url);
        return view('pages.show', ['recipe' => $recipe]);
    }

    public function searching()
    {
        $data = $_POST;
        $data = str_replace(' ', '-', $data);
        return redirect(route('pages.search', $data['search']));
    }

    public function search($title)
    {
        $title = str_replace('-', ' ', $title);
        $title = trim($title);

        $recipes = Recipe::findByTitle($title);
        return view('pages.search', ['recipes' => $recipes, 'search' => $title]);
    }
}
