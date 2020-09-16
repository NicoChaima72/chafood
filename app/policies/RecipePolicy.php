<?php

class RecipePolicy
{
    public static function before()
    {
    }

    public static function show($recipe)
    {
        if (auth()->id !== $recipe->user_id)
            return redirect(route('pages.index'));
    }

    public static function edit($recipe)
    {
        if (auth()->id !== $recipe->user_id)
            return redirect(route('pages.index'));
    }

    public static function update($recipe)
    {
        if (auth()->id !== $recipe->user_id)
            return redirect(route('pages.index'));
    }

    public static function delete($recipe)
    {
        if (auth()->id !== $recipe->user_id)
            return redirect(route('pages.index'));
    }
}
