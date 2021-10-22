<?php

	class Form {
		
		private $data;
		private $errors;
		private static $fields = 
		['task', 'type', 'place', 'date', 'time', 'duration', 'comment'];
		private $dbname = 'mysql';

		public function read_data($data)
		{
			unset($data['submit']);
			$this->data = $data;
		}
		
		public function validate_all()
		{
			$this->validate_task();
			$this->validate_type();
			$this->validate_place();
			$this->validate_duration();
			
			return $this->errors;
		}
		
		public function save_to_db()
		{

			$dbname = 'mysql';
			$table = 'tasks';
			$dsn = 'mysql:host=127.0.0.1;dbname='.$dbname;
			$login = 'root';
			$password = '';

			$pdo = new PDO($dsn, $login, $password);

			$pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
			$pdo->query("use $dbname");
			['task', 'type', 'place', 'date', 'time', 'duration', 'comment'];
			
			$pdo->exec('
				CREATE TABLE IF NOT EXISTS `'. $table . '`(
				`task` VARCHAR(255) NOT NULL,
				`type` VARCHAR(255) NOT NULL,
				`place` VARCHAR(255) NOT NULL,
				`date` DATE,
				`time` TIME,
				`duration` VARCHAR(255) NOT NULL,
				`comment` VARCHAR(255),
				`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
			)');

			
			$sql = $pdo->prepare('INSERT INTO `'. $table . '`
				(`task`, `type`, `place`, `date`, `time`, `duration`, `comment`)
				VALUES (:task, :type, :place, :date, :time, :duration, :comment)');
			$sql->execute([
					':task' => $this->data['task'],
					':type' => $this->data['type'],
					':place' => $this->data['place'],
					':date' => $this->data['date'],
					':time' => $this->data['time'],
					':duration' => $this->data['duration'],
					':comment' => $this->data['comment'],
				]);
			$pdo = null;
			return;
		}
		
		public function read_file($file)
		{
			$contents = file_get_contents($file);
			$contents = unserialize($contents);
			$this->data = $contents;
		}
		
		public function get_data()
		{
			return $this->data;
		}
		
		private function validate_task()
		{
			$task = trim($this->data['task']);
			if (strlen($task) === 0)
			{
				$this->show_error('task','Напишите тему');
			}
			return;	
		}
		
		private function validate_type()
		{
			$type = trim($this->data['type']);
			if (strlen($type) === 0)
			{
				$this->show_error('type','Выберите тип');
			}
			return;	
		}
		
		private function validate_place()
		{
			$place = trim($this->data['place']);
			if (strlen($place) === 0)
			{
				$this->show_error('place','Укажите место (прочерк)');
			}			
			return;	
		}
		
		private function validate_duration()
		{
			$duration = trim($this->data['duration']);
			if (strlen($duration) === 0)
			{
				$this->show_error('duration','Укажите период');
			}			
			return;	
		}
		
		private function show_error($key, $error)
		{
			$this->errors[$key] = $error;
			
			return;
		}
	}
?>