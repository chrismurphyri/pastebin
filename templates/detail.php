   <a href="<?=$app->urlFor("edit", array("id" => $id))?>">Edit</a><br/>
   <a href="<?=$app->urlFor("home")?>">Home</a><br/>
   <a href="<?=$app->urlFor("search")?>">Search</a><br/>
<br/>   <br/>
Title : <?= stripslashes($paste_bin->title) ?> <br/><br/>
Tags : <?= stripslashes($paste_bin->tags) ?> <br/><br/>

        <? if(strstr($paste_bin->tags, "url")) : ?>
<a href="<?=$paste_bin->id?>"><?=$paste_bin->title?></a>
   <? else : ?>

<xmp style="height:800px; width:800px;">
<?= stripslashes($paste_bin->content) ?>
</xmp>

 <?endif; ?>




