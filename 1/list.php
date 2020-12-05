<html>
    <head>
		<link rel="icon" href="/icon.png" type="image/x-icon">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Список рецензий</title>
		<link rel="stylesheet" href="style.css">
		<script src="script.js"></script>
	</head>
    <body>
	<header>
		<nav style = "float:left;">
			<h1>
				Полный список базы данных
			</h1>
			<ul>
				<li><a href="index.php">Главная страница</a></li>
			</ul>
			<button class="toggle-menu" aria-label="Responsive Navigation Menu">☰</button>
		</nav>
	</header>
	<main>
	<div class='error'>
        <fieldset>
            <legend>Список статей</legend>
                <?php
					mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
					$bd = mysqli_connect("localhost", "q9505304_bd", "mami115N", "q9505304_bd") or die (mysql_error ());

                    $SQL_query = "SELECT author_article.id_a, author_article.id_s, author.author, article, bbk, udk, keys_word FROM article, author, author_article 
					WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s";
                    $result = mysqli_query($bd, $SQL_query);
                    echo "
                        <table width='auto' color='black'> 
                        <tr> 
                            <th>Автор</th> 
                            <th>Название статьи</th> 
                            <th>Шифр ББК</th>
                            <th>Шифр УДК</th> 
                            <th>Ключевые слова</th> 
						</tr> ";

                    while($row = mysqli_fetch_array($result))
                    { 
                        $sql_author = "SELECT author_article.id_a, author_article.id_s, author.author, article, bbk, udk, keys_word FROM article, author, author_article 
					    WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s";
					    $authors = mysqli_query($bd, $sql_author);
                        while($result_authors = mysqli_fetch_array($authors))
					    {
					        if($row['id_s'] == $result_authors['id_s'])
					        {
					            if($author == NULL)
					            {
					                $author = $result_authors['author']; 
					            }
					            else
					            {
					             $author = $author.", ".$result_authors['author'];   
					            }
					            
					        }
					        
					    }
                        
                        if($id_s == $row['id_s'])
                        {
                           unset ($author); 
                        }
                        else
                        {
                           $id_s = $row['id_s']; 
                           
                            echo "
                            <tr>
                                <td>".$author."</td>
                                <td>".$row['article']."</td>
                                <td>".$row['bbk']."</td>
                                ";
                                unset ($author);
                            $udk = explode("|", $row['udk']);
							if(count($udk)>1){
							    echo "<td>";
								for($i=0;$i<sizeof($udk);$i++)
								{
									echo "$udk[$i]<br>";
								}
								echo "</td>";
							}
							else {
							    echo "<td>";
							    echo $row['udk'];
							    echo "</td>"; 
							    
							}
							    
							 $keyword = explode("|", $row['keys_word']);
							if(count($keyword)>1){
							    echo "<td>";
								for($i=0;$i<sizeof($keyword);$i++)
								{
									echo "$keyword[$i]<br>";
								}
								echo "</td>";
							}
							else {
							   echo " <td>".$row['keys_word']."</td>";
							    
							}
                           
                           echo "</tr>";
                           
                        }
                        
    
                    }
                	mysqli_free_result($result); //free ram
				?>
		</fieldset>
	</div>
	</main>
	<div class="counter"></div>
		<script>
			var m = document.querySelector("main"),
				h = document.querySelector("header"),
				hHeight;
			
			function setTopPadding() {
				hHeight = h.offsetHeight;
				m.style.paddingTop = hHeight + "px";
			}
			function onScroll() {
				window.addEventListener("scroll", callbackFunc);
				function callbackFunc() {
					var y = window.pageYOffset;
					if (y > 80) {
					h.classList.add("scroll");
					} else {
					h.classList.remove("scroll");
					}
				}
			}
			window.onload = function() {
				setTopPadding();
				onScroll();
			};
			
			window.onresize = function() {
				setTopPadding();
			};
		</script>
	</body>
</html>