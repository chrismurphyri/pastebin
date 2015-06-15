   <a href="<?=$app->urlFor("search")?>">Search</a><br/><br/>
   
   Tags : <?= $search ?><br/><br/>
<?
        foreach($paste_bins as $p) {
          $url = $app->urlFor("detail", array("id" => $p->id));
          echo "<a href=\"$url\">$p->title</a><br/>";
        }
?>        