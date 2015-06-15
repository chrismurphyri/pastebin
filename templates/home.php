  <a href="<?=$app->urlFor("new")?>">New</a><br/>
   <a href="<?=$app->urlFor("search")?>">Search</a><br/><br/>
   
   <table border="1">
     
<?
        foreach($paste_bins as $p) {
          $url = $app->urlFor("detail", array("id" => $p->id));
?>
    <tr>
        <td><a href="javascript:delete_record('<?=$p->id?>')">Delete</a></td>
        <? if(strstr($p->tags, "url")) : ?>
        <td><a href="<?=trim($p->id)?>"><?=stripslashes($p->title)?></a></td>                
        <? else : ?>
        <td><a href="<?=$url?>"><?=stripslashes($p->title)?></a></td>
        <? endif; ?>
    </tr>
<?
        }
 ?>
 </table>
 <br/><br/>