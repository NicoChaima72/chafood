<?php

// requerimos los modelos que se relacionan con el controlador
require_once model_path('Recipe');
require_once model_path('User');
require_once model_path('Type');
require_once model_path('Photo');

class RecipesController extends Controller
{
    // cuando llamamos al controlador en la clase Route y es una url con parametro
    // evaluamos que ese parametro (en este caso la url de la receta) exista
    // si esta no existe, nos devolverá un error 404
    public function handle($url = null)
    // AQUI JUGAREMOS CON EL MIDDLEWARE MAS ADELANTE
    {
        // si no trae ningun parametro, lo dejamos pasar
        if (is_null($url))
            return true;

        // si trae algun parametro, signifa que estamos buscando una receta
        // verificamos si existe
        $recipe = Recipe::findByUrl($url);

        // si no existe devolvemos false lo cual nos arrojará 404
        if (empty($recipe))
            return false;

        // si existe lo dejamos pasar
        return true;
    }

    public function index()
    {
        $recipes = auth()->recipes();
        return view('recipes.index', ['recipes' => $recipes]);
    }

    public function create()
    {
        $types = Type::get();
        return view('recipes.create', ['types' => $types]);
    }

    public function store()
    {
        // esta ruta está definida como post y es para agregar una receta
        // por lo que toda la informacion enviada la guardamos en $request
        $request = CreateRecipeRequest::validate();
        if ($request === false) return;

        // var_dump(File::upload($image, "img", "probando", -1));

        $data = [
            'user_id' => auth()->id,
            'type_id' => @$request['type_id'],
            'title' => @$request['title'],
            'description' => @$request['description'],
            'ingredients' => @$request['ingredients'],
            'steps' => @$request['steps'],
            'duration' => @$request['duration'],
            'persons' => @$request['persons'],
        ];
        $result = Recipe::create($data);

        $photo_data = ['recipe_id' => $result, 'url' => @$request['image']];

        $photo_saved = Photo::create($photo_data);

        // return print_r($photo_saved);

        // $photo_saved = $this->insertImage($request['image'], "img/recipes", $result, 5120);
        // Subimos la imagen al servidor y la registramos en la bd
        // si ha ocurrido un problema con la foto, eliminará la receta recien creada
        // if ($result != 0) {
        //     $photo_saved = $this->insertImage($request['image'], "img/recipes", $result, 5120);
        //     if (!$photo_saved) {
        //         Recipe::find($result)->delete();
        //         $result = 0;
        //     }
        // }
        // enviamos estos mensajes mediante session para actualizar la pag y que desaparezcan
        //(ver helper redirect)
        return redirect(route('recipes.index'), [
            'message' => $result != 0 ? 'Se ha agregado la receta' : 'No se ha agregado la receta',
            'icon' => $result != 0 ? 'fas fa-check' : 'fas fa-times',
            'type' => $result != 0 ? 'bg-green-600' : 'bg-red-600'
        ]);
    }

    public function show($url)
    {
        $recipe = Recipe::findByUrl($url);
        return view('recipes.show', ['recipe' => $recipe]);
    }

    public function edit($url)
    {
        $recipe = Recipe::findByUrl($url);
        RecipePolicy::edit($recipe);
        $types = Type::get();
        return view('recipes.edit', ['recipe' => $recipe, 'types' => $types]);
    }

    public function update($url)
    {
        $recipe = Recipe::findByUrl($url);
        RecipePolicy::update($recipe);

        $request = UpdateRecipeRequest::validate($recipe);
        if ($request === false) return;




        $data = [
            'user_id' => auth()->id,
            'type_id' => @$request['type_id'],
            'title' => @$request['title'],
            'description' => @$request['description'],
            'ingredients' => @$request['ingredients'],
            'steps' => @$request['steps'],
            'duration' => @$request['duration'],
            'persons' => @$request['persons'],
        ];
        
        $recipe->update($data);

        if (@$request['image'] != $recipe->photo()->url) {
            $this->deleteImage($recipe);
            $photo_data = ['recipe_id' => $recipe->id, 'url' => @$request['image']];
            $photo_saved = Photo::create($photo_data);
        }
        
        
        // if (has_file($request['image']) == 1) {
        //     $this->updateImage($recipe, $request['image'], "img/recipes");
        // }

        return redirect(route('recipes.index'), [
            'message' => 'Se ha actualizado la receta',
            'icon' => 'fas fa-check',
            'type' => 'bg-green-600'
        ]);
    }

    public function destroy($url)
    {
        $recipe = Recipe::findByUrl($url);
        RecipePolicy::delete($recipe);

        $this->deleteImage($recipe);
        $recipe->delete();

        return redirect(route('recipes.index'), [
            'message' => 'Se ha eliminado la receta',
            'icon' => 'fas fa-check',
            'type' => 'bg-green-600'
        ]);
    }


    private function insertImage($file, $directory = "", $recipe_id = "", $max_size_kB = -1)
    {
        $url = File::upload($file, $directory, $recipe_id, $max_size_kB);
        if ($url !== 0) {
            $data = ['recipe_id' => $recipe_id, 'url' => $url];
            $photo_saved = Photo::create($data);
            if ($photo_saved != 0)
                return true;
            else
                File::remove();
        }

        return false;
    }

    private function updateImage($recipe, $file, $directory = "", $max_size_kB = -1)
    {
        File::remove($recipe->photo()->url);
        $url = File::upload($file, $directory, $recipe->id, $max_size_kB);
        if ($url !== 0) {
            $photo_saved = Photo::findByRecipe($recipe->id)->update(['url' => $url]);
            var_dump($photo_saved);
        }
        return true;
    }

    private function deleteImage($recipe)
    {
        // File::remove($recipe->photo()->url);
        Photo::findByRecipe($recipe->id)->delete();
        return true;
    }
}
