<?
     require 'vendor/autoload.php';
     date_default_timezone_set('America/New_York');
  
     include_once ("config.php");
  
     # create/initialize the active record connection
     ActiveRecord\Config::initialize(function($cfg)
     {
         global $host, $username, $password, $database;
         $cfg->set_model_directory('models');
         $cfg->set_connections(array(
            'development' => "mysql://$username:$password@$host/$database"));
    });

    $app = new \Slim\Slim();
    
# get the list of pastes    
    $app->get('/', function () use($app) {

        $paste_bins = Pastebin::all();

        # load the list of pastes
        $partial = "home.php";
        include("templates/layout.php");
        
    })->name("home");    
    
# create a new paste
    $app->post('/pastes', function() use($app) {
        
        # specify the paste to be created
        $input["title"]   = $_POST["title"];
        $input["content"] = $_POST["content"];
        $input["tags"]    = $_POST["tags"];
        
        # create the new paste
        $paste = Pastebin::create($input);
        
        # if paste created, redirect to the list of pastes
        if($paste) {
           $app->redirect($app->urlFor("home"));            
        }
        
    })->name("create_paste");
    
# perform a tag search on the list of pastes
    $app->post('/', function () use($app) {
      
        $search = trim ($_POST["search"]);

        # create sql for each term that has wildcards on left side, right side, and both sides
        #   - just apply this function to each item in the list of terms
        
        $terms  = explode(" ", $search);
        $terms  = array_map(function($x) { return "tags LIKE '$x%' OR tags like '%$x' OR tags LIKE '%$x%'"; }, $terms);        
        $terms = join(" AND ", $terms);

        # run the query
        $query["conditions"] = $terms;
        $paste_bins = Pastebin::all($query);
        
        # load the layout template
        $partial = "search.php";
        include("templates/layout.php");
        
    })->name("do_search");
    
# perform a full text search, not a tag search
    $app->post('/do_search_text', function () use($app) {
      
        $search = trim ($_POST["search"]);

        $query["conditions"] = "title LIKE '%$search%' OR content like '%$search%'";
        $paste_bins = Pastebin::all($query);
        
        # load the layout template
        $partial = "search_content.php";
        include("templates/layout.php");
        
    })->name("do_search_text");    
    
# get a form to perform a tag search - this is the one I use the most
    $app->get('/search', function () use($app) {
        # load the search form
        
        $post_url = $app->urlFor("do_search");
        $partial = "search_form.php";
        include("templates/layout.php");
        
    })->name("search");        

# get a form to perform a full text search
    $app->get('/search_text', function () use($app) {
      
        $post_url = $app->urlFor("do_search_text");
      
        $partial = "search_form.php";
        include("templates/layout.php");
        
    })->name("search_text");       
    
# delete a paste
    $app->get('/delete/:id', function ($id) use($app) {
        $paste = Pastebin::find_by_id($id);
        if(!$paste) {
            die("No paste found");
        }
        
        $paste->delete();
        $app->redirect($app->urlFor("home"));        
       
    })->name("delete");  
    
# edit a particular paste
    $app->get('/edit/:id', function ($id) use($app) {
        $paste = Pastebin::find_by_id($id);
        if(!$paste) {
            die("No paste found");
        }
        
        # create the update link - will be used in the template
        $paste_url = $app->urlFor("update_paste", array("id" => $id));
        
        # load the template
        $partial = "edit.php";
        include("templates/layout.php");        
    })->name("edit");  
        
# get a form to create a new paste    
    $app->get('/new', function () use($app) {
      
        $paste_url = $app->urlFor("create_paste");
        
        $partial = "edit.php";
        include("templates/layout.php");
        
    })->name("new");    
    
# get the details of a particular paste    
    $app->get('/:id', function ($id) use($app) {
        
        $paste_bin = Pastebin::find_by_id($id);
        if(!$paste_bin) {
            die("No paste found");
        }
        
        $partial = "detail.php";
        include("templates/layout.php");
        
    })->name("detail");
    
# update the paste
    $app->post('/:id', function ($id) use($app) {
      
        $paste_bin = Pastebin::find_by_id($id);
        if(!$paste_bin) {
            die("No paste found");
        }
        
        $paste_bin->title    = $_POST["title"];
        $paste_bin->content  = $_POST["content"];
        $paste_bin->tags     = $_POST["tags"];
        $paste_bin->save();
        
        $app->redirect($app->urlFor("detail", array("id" => $id)));
        
    })->name("update_paste");
    
# this just runs the slim app we created
    $app->run();


?>