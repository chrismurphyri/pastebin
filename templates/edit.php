    <form method="post" action="<?=$paste_url?>">
       <input type="submit" value="submit"/><br/><br/>
        Title : <input style="width:800px" type="text" name="title" value="<?=stripslashes($paste->title)?>"/><br/>
        Tags : <input style="width:800px" type="text" name="tags" value="<?=stripslashes($paste->tags)?>"/><br/>
        <textarea style="width:800px; height:800px;" name="content"><?=stripslashes($paste->content)?></textarea><br/>
        <input type="submit" value="submit"/>
    </form>
