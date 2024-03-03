<?php
require __DIR__.'/lib/db.inc.php';
ini_set('display_errors', 1);
$res = iems5718_cat_fetchall();
$options = '';

// Again, it is NOT SECURE. Why? and how to make it "secure"?

foreach ($res as $value){
    $options .= '<option value="'.$value["catid"].'"> '.$value["name"].' </option>';
}
?>


<html>
    <fieldset>
        <legend> New Product</legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert"
        enctype="multipart/form-data">
            <label for="prod_catid"> Category *</label>
            <div> <select id="prod_catid" name="catid"><?php echo $options; ?></select></div>
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
            <label for="prod_desc"> Description *</label>
            <div> <textarea id = "prod_desc" name="description" rows="7" cols="30" required="required" pattern="#^[\w\-]+$"> </textarea> </div>
            <label for="prod_image"> Image * </label>
            <div> <input type="file" name="file" required="true" accept="image/jpeg"/> </div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Edit Product</legend>
        <form id="prod_edit" method="POST" action="admin-process.php?action=prod_edit"
        enctype="multipart/form-data">
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^[\w\-]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Delete Products by catid</legend>
        <form id="prod_delete_by_catid" method="POST" action="admin-process.php?action=prod_delete_by_catid"
        enctype="multipart/form-data">
            <label for="prod_catid"> Category *</label>
            <div> <select id="prod_catid" name="catid"><?php echo $options; ?></select></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> New Category</legend>
        <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert"
        enctype="multipart/form-data">
            <label for="cat_name"> Name </label>
            <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Edit Category</legend>
        <form id="cat_edit" method="POST" action="admin-process.php?action=cat_edit"
        enctype="multipart/form-data">
            <label for="cat_catid"> Category *</label>
            <div> <select id="cat_catid" name="catid"><?php echo $options; ?></select></div>
            <label for="cat_name"> Name </label>
	    <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
	    <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Delete Category</legend>
        <form id="cat_delete" method="POST" action="admin-process.php?action=cat_delete"
        enctype="multipart/form-data">
            <label for="cat_catid"> Category *</label>
            <div> <select id="cat_catid" name="catid"><?php echo $options; ?></select></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>

                                                            
