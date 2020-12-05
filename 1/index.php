<html>
	<head>
		<link rel="icon" href="/icon.png" type="image/x-icon">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Загрузка файлов рецензии</title>
		<link rel="stylesheet" href="style.css">
		<script src="script.js"></script>
	</head>
	<body>
		<header>
			<nav style = "float:left;">
				<h1>
					ВООБЩЕ НЕ Главная страница
				</h1>
				<ul>
					<li><a href="list.php">Список</a></li>
					<li><a href="#okno">Загрузка файла</a></li>
					<li><a href="">                             </a></li>
				</ul>			
			</nav>
		</header>
		<main>
			<div id="okno">
				<fieldset>
					<legend>Загрузка файлов на сервер</legend>
						<form enctype="multipart/form-data" method="post"> 
							<p><input type="file" name="file" accept=".tex"></p> 
							<input type=hidden class="close" value="#">
							<p><input type="submit" value="Обработать"></p> 
							<a href="#" class="close">Закрыть окно</a>
						</form>
				</fieldset>
			</div>
				<?php
					if ($_FILES['file']['error']== 0 && $_FILES['file']['name']!=NULL) 
					{
						echo "
							<div class='error'>
						";
						@mkdir("uploads", 0777);
						$dir = "uploads/".$_FILES['file']['name'];
						copy($_FILES['file']['tmp_name'], $dir);
						$string = shell_exec("python parser.py ".$dir);
						if ($string == NULL){
						    shell_exec("iconv -f WINDOWS-1251 -t UTF-8 -o ".$dir." ".$dir);
						    $string = shell_exec("python parser.py ".$dir);
						}
						$value = explode(";", $string);
						echo "
							<table width='auto'> 
							<tr> 
								<th>Автор</th> 
								<th>Название статьи</th> 
								<th>Шифр ББК</th> 
								<th>Шифр УДК</th> 
								<th>Ключевые слова</th>
							</tr> 
							<tr> 
							";
							if($value[0]>1)
							{
								$names = explode("|", $value[1]);
								echo "<td>";
								for($i=0;$i<$value[0];$i++)
								{
									echo "$names[$i]<br>";
								}
								echo "</td>";
							}
							else
								echo "<td>$value[1]</td>";
							$title = explode("|", $value[2]);
							if(count($title)>1){
							    echo "<td>";
								for($i=0;$i<$count($title);$i++)
								{
									echo "$title[$i]<br>";
								}
								echo "</td>";
							}
							else
							    echo "<td>$value[2]</td>";
							$bbk = explode("|", $value[3]);
							if(count($bbk)>1){
							    echo "<td>";
								for($i=0;$i<sizeof($bbk);$i++)
								{
									echo "$bbk[$i]<br>";
								}
								echo "</td>";
							}
							else
							    echo "<td>$value[3]</td>";
							$udk = explode("|", $value[4]);
							if(count($udk)>1){
							    echo "<td>";
								for($i=0;$i<sizeof($udk);$i++)
								{
									echo "$udk[$i]<br>";
								}
								echo "</td>";
							}
							else
							    echo "<td>$value[4]</td>";
							$keyword = explode("|", $value[5]);
							if(count($keyword)>1){
							    echo "<td>";
								for($i=0;$i<sizeof($keyword);$i++)
								{
									echo "$keyword[$i]<br>";
								}
								echo "</td>";
							}
							else
							    echo "<td>$value[5]</td>";
							echo "
							</tr>
							</table>
						";
						?>
							<form action='review.php' method='post'>
							<input type=hidden name='value' value="<?php echo $string ?>" >
							<input type='submit'>
							</form>
						<?php
					}
					else if($_FILES['file']['error']== 1)
					{ 
						echo "
							<div class='error'>
							<h3>Ошибка! Не удалось загрузить файл на сервер!</h3>
						";
						exit; 
					}
					else
					{
						echo "
							<div class='error'>
							<h3>Для отображения списка загрузите файл</h3>
						";
						exit;
					}
				?>	 
			</div>
		</main>
	</body>
</html>