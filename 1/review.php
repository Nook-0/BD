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
				Рекомендации
			</h1>
			<ul>
				<li><a href="index.php">Главная страница</a></li>
				<li><a href="list.php">Список</a></li>
			</ul>
			<button class="toggle-menu" aria-label="Responsive Navigation Menu">☰</button>
		</nav>
	</header>
	<main>
	<form action="" method="post">
	<div class='error'>
        <?php
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$bd = mysqli_connect("localhost", "q9505304_bd", "mami115N", "q9505304_bd") or die (mysql_error ());
			$value = explode(";", $_POST['value']);
            if($value[0]==1)
        	    {
				///////// Загрузка данных на сервер ////////
				$SQL_insert_author = "INSERT INTO author (id_a, author) VALUES(0, '{$value[1]}');";
				$result_insert_author = mysqli_query( $bd, $SQL_insert_author);
				
				$SQL_insert_article = "INSERT INTO article (id_s, article, bbk, udk, keys_word) VALUES(0, '{$value[2]}', '{$value[3]}', '{$value[4]}', '{$value[5]}');";
				$result_insert_article = mysqli_query( $bd, $SQL_insert_article );
					
				///////// Объединение автора и статьи /////////

				$select_for_author = "SELECT id_a FROM author WHERE author = '{$value[1]}'";
				$result_select_author = mysqli_query( $bd, $select_for_author );
				$result_for_author = mysqli_fetch_assoc($result_select_author);
				
				$select_for_article = "SELECT id_s FROM article WHERE article = '{$value[2]}' AND bbk = '{$value[3]}' AND udk = '{$value[4]}' AND keys_word = '{$value[5]}'";
				$result_select_article = mysqli_query( $bd, $select_for_article );
				$result_for_article = mysqli_fetch_assoc($result_select_article);
				
				
				$select_replace = "SELECT * FROM author_article WHERE id_a = '{$result_for_author['id_a']}' AND id_s = '{$result_for_article['id_s']}'";
				$result_select_replace = mysqli_query( $bd, $select_replace );
				$replace = mysqli_fetch_assoc($result_select_replace);
				
				$select_request_for_find = "SELECT author.author FROM article, author, author_article WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s AND ( author.author != '{$value[1]}' AND (article.bbk = '{$value[3]}' OR article.udk = '{$value[4]}' OR article.keys_word = '{$value[5]}'))";
				$request_for_find = mysqli_query( $bd, $select_request_for_find );
				
				
				if($replace['id_a'] == NULL AND $replace['id_s'] == NULL) 
				{
					$insert_author_article_table = "INSERT INTO author_article (id_a, id_s) VALUES ('{$result_for_author['id_a']}', '{$result_for_article['id_s']}');";
					mysqli_query( $bd, $insert_author_article_table );
				}
				///////// Определение списка или рекомендации /////////

                $analiz_dann = "SELECT id_s, bbk, udk, keys_word FROM article WHERE bbk = '{$value[3]}' AND udk = '{$value[4]}' AND keys_word = '{$value[5]}'";
                $result_analiz_dann = mysqli_query( $bd, $analiz_dann ); 
                $all_result_analiz_dann = (mysqli_fetch_array($result_analiz_dann)); 
				if($all_result_analiz_dann != NULL)
				{ 
    				    $analiz_author = "SELECT author.id_a, author.author FROM article, author, author_article WHERE author.id_a = author_article.id_a AND '{$all_result_analiz_dann['id_s']}' = author_article.id_s AND ( author.author != '{$value[1]}' )"; 
                        $result_author_dann = mysqli_query( $bd, $analiz_author ); 
    				    $all_result_author_dann = (mysqli_fetch_array($result_author_dann)); 
    				if(mysqli_fetch_array($result_author_dann) == NULL)
    				{
        					echo "
        						<fieldset>
                				<legend>Список</legend>
        						<table width='auto'>
        						<tr> 
        							<th> № </th>
        							<th>Автор</th> 
        						</tr> 
        					";
                            $select_all_tables = "SELECT author.id_a, author.author, article, bbk, udk, keys_word FROM article, author, author_article WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s AND author_article.id_a != '{$replace['id_a']}' AND author_article.id_s != '{$replace['id_s']}' AND ( author.author != '{$value[1]}' )";
        					$list = mysqli_query( $bd, $select_all_tables );
        					while($end_list = mysqli_fetch_array($list))
        					{ 
        						echo "
        							<tr>
        								<td><input type='radio' name='choose' value='".$end_list['id_a']."'></td>
        								<td>".$end_list['author']."</td>
        							</tr>
        						";
        					}
        					echo " 
        						</table>
        						</fieldset>
        						<input type='submit'>
        					";
    					}
    				else
    				{ 
    					$select_find_value = "SELECT author.id_a, author.author FROM article, author, author_article WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s AND (author.author != '{$value[1]}' AND (article.bbk = '{$value[3]}' OR article.udk = '{$value[4]}' OR article.keys_word = '{$value[5]}'))";
    					$find_value = mysqli_query( $bd, $select_find_value );
    					echo "
    						<fieldset>
            				<legend color='black'>Рекомендации</legend>
    						<table width='auto'> 
    						<tr> 
    							<th> № </th>
    							<th>Автор</th>  
    						</tr> 
    					";
    					while($find = mysqli_fetch_array($find_value))
    					{
    						echo "
    							<tr>
    								<td><input type='radio' name='choose' value='".$find['id_a']."'></td>
    								<td>".$find['author']."</td>
    							</tr>
    						";
    					}
    					echo "
    						</table>
    						</fieldset>
    						<input type='submit'>
    					";
    				} 
				}
            }
                if($value[0]>1)
                {
					$insert_article = "INSERT INTO article (id_s, article, bbk, udk, keys_word) VALUES(0, '{$value[2]}', '{$value[3]}', '{$value[4]}', '{$value[5]}');";
                    mysqli_query($bd, $insert_article);
					
					$select_article = "SELECT id_s FROM article WHERE article = '{$value[2]}' AND bbk = '{$value[3]}' AND udk = '{$value[4]}' AND keys_word = '{$value[5]}'";
					$result_for_select_article = mysqli_query( $bd, $select_article );
					$result_for_article = mysqli_fetch_assoc($result_for_select_article);
					
					$authors = explode("|", $value[1]);
					$udks = explode("+", $value[4]);
					$str = " ";
					$str_udk = " ";
					$select_resplit_udk = "SELECT id_s, udk FROM article";
					$resplit_udk = mysqli_query( $bd, $select_resplit_udk );
					while($resplit = mysqli_fetch_array($resplit_udk))
					{
						$temp = explode("+",$resplit['udk']);
						for($i=0;$i<count($udks);$i++)
						{
							for($j=0;$j<count($temp);$j++)
							{
								if($temp[$j]==$udks[$i])
								{
									$str_udk = $str_udk." article.id_s = '".$resplit['id_s']."' OR ";
									$i++;
									break;
								}
							}
						}
					}
					$str_udk = substr($str_udk, 0, -3);
                    for($i=0;$i<count($authors);$i++)
                    {
						$insert_author = "INSERT INTO author (id_a, author) VALUES(0, '{$authors[$i]}');";
                        mysqli_query( $bd, $insert_author );
						
						$select_author = "SELECT id_a FROM author WHERE author = '{$authors[$i]}'";
						$select_result_for_author = mysqli_query( $bd, $select_author );
						$result_for_author[$i] = mysqli_fetch_assoc( $select_result_for_author );
						
						$select_author_article_table = "SELECT * FROM author_article WHERE id_a = '{$result_for_author[$i]['id_a']}' AND id_s = '{$result_for_article['id_s']}'";
						$replace_select_author_article_table = mysqli_query( $bd, $select_author_article_table );
						$replace = mysqli_fetch_assoc( $replace_select_author_article_table );
						if($replace['id_a'] == NULL AND $replace['id_s'] == NULL)
						{
							$insert2_author_article_table = "INSERT INTO author_article (id_a, id_s) VALUES ('{$result_for_author[$i]['id_a']}','{$result_for_article['id_s']}');";
							mysqli_query( $bd, $insert2_author_article_table );
						}
						
						///////// Определение списка или рекомендации /////////
						if($i<count($authors)-1)
							$str = $str." `author`.`author` != '".$authors[$i]."' AND ";
						else
							$str = $str." `author`.`author` != '".$authors[$i]."' ";
					}
					
					$analiz_dann = "SELECT id_s, bbk, udk, keys_word FROM article WHERE bbk = '{$value[3]}' AND ({$str_udk}) AND keys_word = '{$value[5]}'";
                    $result_analiz_dann = mysqli_query( $bd, $analiz_dann ); 
                    $all_result_analiz_dann = (mysqli_fetch_array($result_analiz_dann)); 
    				if($all_result_analiz_dann != NULL)
    				{
        				    $analiz_author = "SELECT author.id_a, author.author FROM article, author, author_article WHERE author.id_a = author_article.id_a AND '{$all_result_analiz_dann['id_s']}' = author_article.id_s AND ( ({$str}) )"; 
                            $result_author_dann = mysqli_query( $bd, $analiz_author ); 
        				    $all_result_author_dann = (mysqli_fetch_array($result_author_dann));  
        				    
    					if($all_result_author_dann == NULL)
    					{
        						echo "
        							<fieldset>
                    				<legend>Список</legend>
        								<table width='auto'> 
        								<tr> 
        									<th> № </th>
        									<th>Автор</th> 
        								</tr> 
        						";
        						$select_list = "SELECT author.id_a, author.author, article, bbk, udk, keys_word FROM article, author, author_article WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s AND author_article.id_a != '{$replace['id_a']}' AND author_article.id_s != '{$replace['id_s']}' AND ({$str})";
        	    				$list = mysqli_query( $bd, $select_list ); 
        						while($end_list = mysqli_fetch_array($list))
        						{
        							echo "
        								<tr>
        									<td><input type='radio' name='choose' value='".$end_list['id_a']."'></td>
        									<td>".$end_list['author']."</td>
        								</tr>
        							";
        						}
        						echo "
        							</table>
        							</fieldset>
        							<input type='submit'>
        						";
    					}
    					else
    					{
    						$select_find_value = "SELECT author.author, author.id_a FROM article, author, author_article WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s AND (({$str}) AND (article.bbk = '{$value[3]}' OR {$str_udk} OR article.keys_word = '{$value[5]}'))";
    						$find_value = mysqli_query( $bd, $select_find_value );						
    						echo "
    							<fieldset>
                				<legend>Рекомендации</legend>
    							<table width='auto'> 
    							<tr> 
    								<th> № </th>
    								<th>Автор</th>  
    							</tr>
    						";
    						while($find = mysqli_fetch_array($find_value))
    						{
    							echo "
    								<tr>
    									<td><input type='radio' name='choose' value='".$find['id_a']."'></td>
    									<td>".$find['author']."</td>
    								</tr>
    							";
    						}
    						echo "
    							</table>
    							</fieldset>
    							<input type='submit'>
    						";
    					}
    				}
				}
        ?>
	</div>
<?php if($_POST['choose'] != NULL)
{?>
	<div class='error'>
		<fieldset>
		<legend> Выбранный автор </legend>
		<table width = "auto">
		<tr>
			<th>Автор</th> 
			<th>Название статьи</th> 
			<th>Шифр ББК</th>
			<th>Шифр УДК</th> 
			<th>Ключевые слова</th> 
		</tr>
		<?php 
		$select_list = "SELECT author.id_a, author.author, article, bbk, udk, keys_word FROM article, author, author_article WHERE author.id_a = author_article.id_a AND article.id_s = author_article.id_s AND author_article.id_a != '{$replace['id_a']}' AND author_article.id_s != '{$replace['id_s']}' AND author.id_a = '{$_POST['choose']}'";
		$list = mysqli_query( $bd, $select_list );
		$number_list = mysqli_fetch_array($list);
		echo "<tr>";
			echo "<td>".$number_list['author']."</td>";
			$title = explode("|", $number_list['article']);
			if(count($title)>1){
			    echo "<td>";
				for($i=0;$i<$count($title);$i++)
				{
					echo $title[$i]."<br>";
				}
				echo "</td>";
			}
			else
			    echo "<td>".$number_list['article']."</td>";
			$bbk = explode("|", $number_list['bbk']);
			if(count($bbk)>1){
			    echo "<td>";
				for($i=0;$i<sizeof($bbk);$i++)
				{
					echo $bbk[$i]."<br>";
				}
				echo "</td>";
			}
			else
			    echo "<td>".$number_list['bbk']."</td>";
				$udk = explode("|", $number_list['udk']);
				if(count($udk)>1){
				    echo "<td>";
					for($i=0;$i<sizeof($udk);$i++)
					{
						echo $udk[$i]."<br>";
					}
					echo "</td>";
				}
				else
				    echo "<td>".$number_list['udk']."</td>";
					$keyword = explode("|", $number_list['keys_word']);
					if(count($keyword)>1){
					    echo "<td>";
						for($i=0;$i<sizeof($keyword);$i++)
						{
							echo $keyword[$i]."<br>";
						}
						echo "</td>";
					}
			    	else
					    echo "<td>".$number_list['keys_word']."</td>";
		echo "
			</tr>";
		?>
	</div>
<?php } ?>	
	</main>
</html>