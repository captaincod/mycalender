<?php
	
	include_once('form.php');
	if (isset($_POST['submit']))
	{
		$form = new Form();
		$form->read_data($_POST);
		$errors = $form->validate_all();
		
		if (empty($errors))
		{
			$form->save_to_db();
			echo 'Заявка принята';
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
    <title>Мой календарь</title>
  </head>
  <body>
  	<form method="POST">
      <table>
          <caption><b>Мой календарь</b></caption>
          <tr>
              <td>
              	<legend>Новая задача</legend>
                <fieldset>
                  <p class="text">Тема <input type="text" name="task">
                  <b class="error"><?php echo $errors['task'] ?? ''?></b>
                  </p>
                  <p class="text" >Тип
                  <input type="text" list="types" name="type">
                  <b class="error"><?php echo $errors['type'] ?? ''?></b>
                  <datalist id="types">
                      <option value="Встреча"></option>
                      <option value="Звонок"></option>
                      <option value="Совещание"></option>
                      <option value="Дело"></option>
                	</datalist></p>
                	<p class="text">Место <input type="text" name="place">
                	<b class="error"><?php echo $errors['place'] ?? ''?></b>
                	<p class="text">Дата и время 
                		<input type="date" name="date" class="date" required>
                		<input type="time" name="time" class="time" required>
                	<p class="text">Длительность
                  <input type="text error" list="durations" name="duration">
									<b class="error"><?php echo $errors['duration'] ?? ''?></b>
                  <datalist id="durations">
                  		<option value="30 минут"></option>
                      <option value="1 час"></option>
                      <option value="1 час 30 минут"></option>
                      <option value="2 часа"></option>
                	</datalist></p>
                	<p>Комментарий
   									<p><textarea name="comment"></textarea></p>
   								</p>
   								<p><input type="submit" name="submit" value="Добавить" class="submit"></p>
                </fieldset>
              </td>
          </tr>
              <td>
              	<legend>Список задач</legend>
                <fieldset>
                	<!--
                  <input type="text" list="statuses" name="status">
                  <datalist id="statuses">
                      <option value="Текущие задачи"></option>
                      <option value="Просроченные задачи"></option>
                      <option value="Выполненные задачи"></option>
                	</datalist></p>
                	-->
                	<p><table>
										<tr>
											<th>Тип</th>
											<th>Задача</th>
											<th>Место</th>
											<th>Дата</th>
											<th>Время</th>
										</tr>
										<?php
											$dbname = 'mysql';
											$table = 'tasks';
											$dsn = 'mysql:host=127.0.0.1;dbname='.$dbname;
											$login = 'root';
											$password = '';

											$pdo = new PDO($dsn, $login, $password);
											$pdo->query("use $dbname");
											$select = $pdo->query('SELECT * FROM `'.$table.'`');
											$tasks = $select->fetchAll(PDO::FETCH_ASSOC);
											foreach ($tasks as $task)
											{
												echo '<tr><td>'.$task['task'].'</td>';
												echo '<td>'.$task['type'].'</td>';
												echo '<td>'.$task['place'].'</td>';
												echo '<td>'.$task['date'].'</td>';
												echo '<td>'.$task['time'].'</td>';
												//echo '<td><input type="checkbox" name="delete"></td>'
												echo '</tr>';
											}
											$pdo = null;
										?>
									</table></p>
                </fieldset>
              </td>
          </tr>
      </table>
      </form>
  </body>
</html>