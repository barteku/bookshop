<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title></title>
        
    </head>
    <body>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="username"/><br/>
            <input type="password" name="password" placeholder="password" /><br/>
            <input type="submit" value="submit" />
        </form>
        <br/><br/><br/>
        <h2>Book</h2>
        <form action="book_create.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="title"/><br/>
            <input type="text" name="authors" placeholder="authors"/><br/>
            <input type="number" name="price" placeholder="price" /><br/>
            <textarea name="description" cols="50" rows="3" placeholder="description"></textarea><br/>
            <label for="image">image:</label><input id="image" type="file" name="image" /><br/>
            <label for="content">content:</label><input id="content" type="file" name="content" /><br/>
            
            <input type="submit" value="submit" />
        </form>
        <br/><br/><br/>
        <h2>Review</h2>
        <form action="review_create.php" method="post">
            <input type="text" name="book_id" placeholder="book"/><br/>
            <input type="number" name="rating" placeholder="rating" /><br/>
            <textarea name="review" cols="50" rows="3" placeholder="review"></textarea><br/>
            <input type="submit" value="submit" />
        </form>
        <br/><br/><br/>
    </body>
</html>