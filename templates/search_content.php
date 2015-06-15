   <a href="<?=$app->urlFor("search_text")?>">Search Text</a><br/><br/>
   
<?
        foreach($paste_bins as $p) {
          $url = $app->urlFor("detail", array("id" => $p->id));
          echo "<a href=\"$url\">$p->title</a><br/>";
        }
?>        