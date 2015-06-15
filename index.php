<?
  require 'vendor/autoload.php';
  date_default_timezone_set('America/New_York');
  
  include_once ("config.php");
  
  ActiveRecord\Config::initialize(function($cfg)
  {
     global $host, $username, $password, $database;
     $cfg->set_model_directory('models');
     $cfg->set_connections(array(
         'development' => "mysql://$username:$password@$host/$database"));
  });

    $app = new \Slim\Slim();
    
    $app->get('/', function () use($app) {
        $paste_bins = Pastebin::all();

        $partial = "home.php";
        include("templates/layout.php");
    })->name("home");    
    
    $app->post('/pastes', function() use($app) {
        $input["title"] = $_POST["title"];
        $input["content"] = $_POST["content"];
        $input["tags"] = $_POST["tags"];
        
        $paste = Pastebin::create($input);
        if($paste) {
           $app->redirect($app->urlFor("home"));            
        }
    })->name("create_paste");
    
    $app->post('/', function () use($app) {
        $search = $_POST["search"];
        $search = trim($search);
        $terms = explode(" ", $search);
        $terms = array_map(function($x) { return "tags LIKE '$x%' OR tags like '%$x' OR tags LIKE '%$x%'"; }, $terms);        

        $terms = join(" AND ", $terms);

        #echo $terms;
        $query["conditions"] = $terms;
        $paste_bins = Pastebin::all($query);
        
        $partial = "search.php";
        include("templates/layout.php");
    })->name("do_search");
    
    $app->post('/do_search_text', function () use($app) {
        $search = $_POST["search"];
        $search = trim($search);
        $x = $search;
        $query["conditions"] = "title LIKE '%$x%' OR content like '%$x%'";
        $paste_bins = Pastebin::all($query);
        
        $partial = "search_content.php";
        include("templates/layout.php");
    })->name("do_search_text");    
    
    $app->get('/search', function () use($app) {
        $post_url = $app->urlFor("do_search");
        $partial = "search_form.php";
        include("templates/layout.php");             
    })->name("search");        

    $app->get('/search_text', function () use($app) {
        $post_url = $app->urlFor("do_search_text");
        $partial = "search_form.php";
        include("templates/layout.php");             
    })->name("search_text");       
    
    $app->get('/delete/:id', function ($id) use($app) {
        $paste = Pastebin::find_by_id($id);
        if(!$paste) {
            die("No paste found");
        }
        
        $paste->delete();
        $app->redirect($app->urlFor("home"));        
       
    })->name("delete");  
    
    $app->get('/edit/:id', function ($id) use($app) {
        $paste = Pastebin::find_by_id($id);
        if(!$paste) {
            die("No paste found");
        }
        $paste_url = $app->urlFor("update_paste", array("id" => $id));
        $partial = "edit.php";
        include("templates/layout.php");        
    })->name("edit");  
        
    $app->get('/new', function () use($app) {
        
        error_reporting(E_ALL & ~E_NOTICE);
        $paste_url = $app->urlFor("create_paste");
        
        $partial = "edit.php";
        include("templates/layout.php");
    })->name("new");    
    
    $app->get('/:id', function ($id) use($app) {
        $paste_bin = Pastebin::find_by_id($id);
        if(!$paste_bin) {
            die("No paste found");
        }
        $partial = "detail.php";
        include("templates/layout.php");
    })->name("detail");
    
    $app->post('/:id', function ($id) use($app) {
        $paste_bin = Pastebin::find_by_id($id);
        if(!$paste_bin) {
            die("No paste found");
        }
        
        $paste_bin->title = $_POST["title"];
        $paste_bin->content = $_POST["content"];
        $paste_bin->tags = $_POST["tags"];
        $paste_bin->save();
        
        $app->redirect($app->urlFor("detail", array("id" => $id)));
        
    })->name("update_paste");
    
    $app->run();


?>