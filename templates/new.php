    <form method="post" action="<?=$paste_url?>">
        <input style="width:800px" type="text" name="title" value="<?=$paste->title?>"/><br/>
        <textarea style="width:800px; height:800px;" name="content"><?=$paste->content?></textarea><br/>
        <input type="submit" value="submit"/>
    </form>