# Laravel 5.5 API From Scratch Using Resources

This is a tutorial to create API in PHP/Laravel Framework which is followed on youtube. [a link][1]

[1]: https://www.youtube.com/watch?v=4pc6cgisbKE 

# 1- How to Create A Migration?

`php artisan make:migration create_articles_table --create=articles`

# 2- How to Change Default String Length?

1. Go to app\Providers\AppServiceProvider.php

	
~~~~~~~~
add use Illuminate\Support\Facades\Schema; //for to use Schema
add into boot() { 
	
	Schema::defaultStringLength(191);

}
	
~~~~~~~~

# 3- How to Create Article Seeder?

`php artisan make:seeds ArticlesTableSeeder`

1. Go to database\seeds\ArticlesTableSeeder.php

~~~~~~~~
add factory(App\Article::class, 30)->create();

to the run() function
~~~~~~~~

# 4- How to Make ArticlesTableSeeder is the default Seeder?

1. Go to seeds\DatabaseSeeder.php 

~~~~~~~~
add $this->call(ArticlesTableSeeder.php) to run()
~~~~~~~~

# 5- How to Use A Model For Seeder? 

~~~~~~~~
php artisan make:factory ArticleFactory

add $factory->define(App\Article::class, function (Faker $faker) {
    return [
        //to format the fake data
        'title' => $faker->text(50), //50 characters
        'body'  => $faker->text(200) 
    ];
});
~~~~~~~~

# 6- How to Create A Model?

~~~~~~~~
php artisan make:model Article
~~~~~~~~

# 7- How To Run migrations?

~~~~~~~~
php artisan migrate 
php artisan migrate:refresh //Optional --seed to renew all migrations
~~~~~~~~

# 8- How To Run Seeds (to generate fake articles)?

~~~~~~~~
php artisan db:seed
~~~~~~~~

# 9- How To Create Controller Class For Article?

~~~~~~~~
php artisan make:controller ArticleController --resource

it will create an controller in App\Http\Controllers\ArticleController and they will have index, update, destroy and etc.. function as default.

We do not need create and update instead we will use store for both. Also delete edit as well.
~~~~~~~~

# 10- How To Define Routes For API?

1. Go to routes folder in api.php

~~~~~~~~
//List articles

Route::get('articles','ArticleController');

***** If you write get it will give error because didn't define the function here so change it to resource *****

Route::resource('articles','ArticleController');


//List single article
Route::get('article/{i}', 'ArticleController@show');

//Create a new article
Route::post('article', 'ArticleController@store');

//Update article
Route::put('article', 'ArticleController@store');

//Delete article
Route::delete('article/{i}', 'ArticleController@destroy');

//@function_name
~~~~~~~~

# 11 - How To Create a Resource?

~~~~~~~~
php artisan make:resource Article

it create a Resources folder in Http\Controllers\Resources
in this folder there will be Article.php

there is an method array which return all articles 
~~~~~~~~

# 12- How To Configure Resource To Return Just Id Title And Body?

app\Http\Resources\Article

~~~~~~~~
//return parent::toArray($request);

return [
    'id' => $this->id,
    title' => $this->title,
    'body' => $this->body
];
~~~~~~~~

# 13- How To Return Single Article?

~~~~~~~~
ArticleController.php ->    

 public function show($id){

	// Get article -> Article:: is a model invoke
	$article = Article::findOrFail($id);

	// Return single article as a resource
	return new ArticleResource($article);

	 //this will call the Resources\Article
}
~~~~~~~~    

# 14- How To Remove Data Field In Single Json Response?

~~~~~~~~
Method 1- 

In default it returns

data : [
	{
		'id',
		'title',
		'body'
	}
]

To remove the data for single article
~~~~~~~~

1. Go to Providers\AppServiceProvider.php

~~~~~~~~
add use Illuminate\Http\Resources\Json\Resource;

in boot() function add

Resource::withoutWrapping();
~~~~~~~~

# 15- How To Return Another Useful Information With The Api

1. Go to Resources\Article.php

~~~~~~~~
add this function     public function with($request){
        return [
            'version' => '1.0.0',
            'author_url' => url('http://olgunozoktas.com')
        ];
    }
~~~~~~~~

# 16- How To Make A Post Request In Postman?

~~~~~~~~
Headers

Key:  Content-Type application/json

Body -> raw

{
	"title":"Test Title",
	"body":"Test Body"
}
~~~~~~~~

# 17- How To Make A Put Request In Postman?

~~~~~~~~
Headers

Key:  Content-Type application/json

Body -> raw

{
	"article_id": 4,
	"title":"Updated Title",
	"body":"Updated Body"
}
~~~~~~~~

# 18- The Update / Post Methods (Others As Well)

1. Go To app\Http\Controllers\ArticleController.php

~~~~~~~~
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get Articles
        $articles = Article::paginate(15);

        //Return collection of articles as a resource
        return ArticleResource::collection($articles);

        //If u want to return a list then you have to write ::collection
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //eğer put methodu ise içerisinde article_id olacaktir
        $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

        $article->id = $request->input('article_id');
        $article->title = $request->input('title');
        $article->body = $request->input('body');

        if($article->save()){
            return new ArticleResource($article);
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
        // Get article -> Article:: is a model invoke
        $article = Article::findOrFail($id);

        // Return single article as a resource
        return new ArticleResource($article);

        //this will call the Resources\Article
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get article
        $article = Article::findOrFail($id);

        if($article->delete()) {
            return new ArticleResource($article);
        }
    }

    EOF
~~~~~~~~






