<?php require_once '../load_functions.php' ?>
<div id="no-composer">
	<form action="index.php" method="POST">
		<label for='id_name'>Nom du projet</label>
		<input type="text" id="id_name" name="project_name" />
		<?php afficher_input($input_form); ?>
		<input type='submit' class='btn_submit' name='submit' value='Envoyer' id='submit'/>
	</form>
</div>

<?php 

if (isset($_POST['submit'])) {

	$input = $_POST['project_name'];
		// On vérifie que ce soit un nom de dossier valide
	if (!preg_match('#[a-zA-Z0-9]#', $input) || empty($input)) { 

		echo "<span style='color: red;'>Veuillez entrer un nom valide</span>";

	} else {
		if (is_dir($input)) {
		echo "<span style='color: red';>Le dossier existe déjà</span>";
		} else {

			/*if(isset($_POST['modele'])) {
				@mkdir("./your-projects/".$input);
				@mkdir("./your-projects/".$input."/resources");
				@mkdir("./your-projects/".$input."/public");
			} else { */
				mkdir("./your-projects/".$input);
				mkdir("./your-projects/".$input.'/css');
				mkdir("./your-projects/".$input.'/js');

				// On créer index.php
				$index = fopen("./your-projects/".$input."/index.php", "w");

				$doctype = "<!DOCTYPE html>\n<html lang=\"fr\">\n<head>\n	<meta charset=\"UTF-8\">\n	<title>".$input."</title>"."\n"; // CREATION DU DEBUT DU DOCTYPE
				fwrite($index, $doctype);

				/***************** CSS ******************/

			//} FIN IF ISSET MODEL

			if (isset($_POST['css'])) {
				mkdir("./your-projects/".$input."/vendor");

				for ($i=0; $i < count($_POST['css']) ; $i++) { 
					$val_input = $_POST['css'][$i];
					$val = $input_form[4]['id'];
					if($val_input == $val) { // si bootstrap
							$src = "./vendor/".$val;
							$dst = "./your-projects/".$input."/vendor/".$val;
							if (is_dir($src)) {
								copydir($src, $dst); // COPIE LES FICHIERS  
							}

						$insert = "	<!-- ".$input_form[4]['label']." CSS -->\n	<link rel=\"stylesheet\" href=\"".$input_form[4]['link']['css']."\">\n";
						fwrite($index, $insert);
					}
				}
				
				foreach ($input_form as $value) { // ON RECUPERE LES VALUES
					for ($i=0; $i<count($_POST['css']); $i++) {
						$val = $_POST['css'][$i];
						if($value['id'] == $val[0]) {
							if(isset($value['link'])) { // ON VERIFIE QU'IL Y A UN LINK DANS LE ARRAY
								if(isset($value['link']['css'])) { // ON RECUPERE LE CSS
									$src = "./vendor/".$value['id'];
									$dst = "./your-projects/".$input."/vendor/".$value['id'];
									if (is_dir($src)) {
										copydir($src, $dst); // COPIE LES FICHIERS  
									}
									$css = $value['link']['css'];
									$insert = "	<!-- ".$value['label']." CSS -->\n 	<link rel=\"stylesheet\" href=\"".$css."\">\n";
									fwrite($index, $insert);
								}
							}

						}
					}
				}

					foreach ($input_form as $value) { // ON RECUPERE LES VALUES
					if(isset($_POST['plugins'])) {

						if(isset($value['plugins'])){ // ON VERIFIE S'IL Y A DES PLUGINS
							foreach ($value['plugins'] as $key) {
								for ($i=0; $i < count($_POST['plugins']); $i++) { 
									$val = $_POST['plugins'][$i];
									if($val == $key['id']) {
										if(isset($key['link']['css'])) {
											$src = "./vendor/".$key['id'];
											$dst = "./your-projects/".$input."/vendor/".$key['id'];
											if (is_dir($src)) {
												copydir($src, $dst); // COPIE LES FICHIERS  
											}
											$pcss = $key['link']['css'];// ON RECUPERE LE CSS
											$insert = "	<!-- ".$key['label']." CSS -->\n 	<link rel=\"stylesheet\" href=\"".$pcss."\">\n";
											fwrite($index, $insert);
												
										}
									}
								}
							}	
						}
					}
				}
			} // FIN CSS


			$css = fopen("your-projects/".$input."/css/style.css", "w"); // ON CREER LE CUSTOM CSS (toujours en dernier)
			// Ici on peut initier un thème initial (ou plutôt importer un custom css de base)
			fclose($css);

			$doctype = "	<!-- CUSTOM CSS -->\n	<link rel=\"stylesheet\" href=\"css/style.css\">\n</head>\n<body>\n\n"; // FIN DOCTYPE
			fwrite($index, $doctype);


			/************** INCLUDE *************/

			if (isset($_POST['include'])) {

				for ($i=0; $i < count($input_form) ; $i++) { 
						for ($j=0; $j < count($_POST['include']) ; $j++) { 
							$val_input = $_POST['include'][$j][$j];
							$val = $input_form[$i]['id'];
							if($val_input == $val) {
								$src = "./include/".$val;
								$dst = "./your-projects/".$input."/";
								copydir($src, $dst); // COPIE LES FICHIERS 
								$include = "	<?php include('".$val.".php'); ?>\n";
								fwrite($index, $include);
						}
					}
				}
			}

			fwrite($index, "\n\n\n\n"); // Rajoute juste les CR à la fin du body

			/************** JS ******************/

			if (isset($_POST['js'])) {

				for ($i=0; $i < count($_POST['js']) ; $i++) { 
					$val_input = $_POST['js'][$i];
					$val = $input_form[3]['id'];
					if($val_input == $val) { // si JQuery
						$src = "./vendor/".$val;
						$dst = "./your-projects/".$input."/vendor/".$val;
						if (is_dir($src)) {
							copydir($src, $dst); // COPIE LES FICHIERS  
						}
						$insert = "	<!-- ".$input_form[3]['label']." JS -->\n 	<script src=\"".$input_form[3]['link']['js']."\"></script>\n";
						fwrite($index, $insert);
					}
				}
				
				foreach ($input_form as $value) { // ON RECUPERE LES VALUES
					for ($i=0; $i<count($_POST['js']); $i++) {
						$val = $_POST['js'][$i];
						if($value['id'] == $val[0]) {
							if(isset($value['link'])) { // ON VERIFIE QU'IL Y A UN LINK DANS LE ARRAY
								if(isset($value['link']['js'])) { // ON RECUPERE LE JS
									$src = "./vendor/".$value['id'];
									$dst = "./your-projects/".$input."/vendor/".$value['id'];
									if (is_dir($src)) {
										copydir($src, $dst); // COPIE LES FICHIERS  
									}
									$js = $value['link']['js'];
									$insert = "	<!-- ".$value['label']." JSS -->\n 	<script src=\"".$js."\"></script>\n";
									fwrite($index, $insert);
								}
							}

						}
					}
				}

					foreach ($input_form as $value) { // ON RECUPERE LES VALUES
					if(isset($_POST['plugins'])) {

						if(isset($value['plugins'])){ // ON VERIFIE S'IL Y A DES PLUGINS
							foreach ($value['plugins'] as $key) {
								for ($i=0; $i < count($_POST['plugins']); $i++) { 
									$val = $_POST['plugins'][$i];
									if($val == $key['id']) {
										if(isset($key['link']['js'])) { // ON RECUPERE LE JS
											$src = "./vendor/".$key['id'];
											$dst = "./your-projects/".$input."/vendor/".$key['id'];
											if (is_dir($src)) {
												copydir($src, $dst); // COPIE LES FICHIERS  
											}
											$pjs = $key['link']['js'];
											$insert = "	<!-- ".$key['label']." JS -->\n 	<script src=\"".$pjs."\"></script>\n";
											fwrite($index, $insert);
										}
									}
								}
							}	
						}
					}
				}
			} // FIN JS

			$js = fopen("your-projects/".$input."/js/script.js", "w"); // ON CREER LE JS
			$function = "$(function(){ \n";
			fwrite($js, $function);	
			$function = "});";
			fwrite($js, $function);	
			fclose($js);
		
			$doctype = "</body>\n</html>";
			fwrite($index, $doctype);

		}
	}
}


?>