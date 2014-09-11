<?php 

class FormElement { 

	// Methods
	public function input( $settings = array(), $values = array() )
	{
		// Setting the defaults
		if ( !empty($settings["id"]) ) { $id = $settings["id"]; } else { $id = ""; }
		if ( !empty($settings["type"]) ) { $type = $settings["type"]; } else { $type = "text"; }
		if ( !empty($settings["class"]) ) { $class = $settings["class"]; } else { $class = ""; }
		if ( !empty($settings["inputClass"]) ) { $inputClass = $settings["inputClass"]; } else { $inputClass = ""; }
		if ( !empty($settings["required"]) ) { $required = $settings["required"]; } else { $required = ""; }
		if ( !empty($settings["inlineCss"]) ) { $inlineCss = $settings["inlineCss"]; } else { $inlineCss = ""; }
		if ( !empty($settings["value"]) ) { $value = $settings["value"]; } else { $value = ""; }
		if ( !empty($settings["placeholder"]) ) { $placeholder = $settings["placeholder"]; } else { $placeholder = ""; }
		if ( !empty($settings["label"]) ) { $label = $settings["label"]; } else { $label = ""; }
		if ( !empty($settings["labelID"]) ) { $labelID = $settings["labelID"]; } else { $labelID = ""; }
		if ( !empty($settings["labelClass"]) ) { $labelClass = $settings["labelClass"]; } else { $labelClass = ""; }
		if ( !empty($settings["more"]) ) { $more = $settings["more"]; } else { $more = false; }
		if ( !empty($settings["name"]) ) { $name = $settings["name"]; } else { $name = ""; }
		if ( !empty($settings["autocomplete"]) ) { $autocomplete = true; } else { $autocomplete = false; }

		$lang = "eng";
		$attribute_name = "name";
		if (isset($_SESSION["lang"])) {
			$lang = $_SESSION["lang"];
			if ($lang == "nor") {
				$attribute_name = "name_nor";
			}
		}	

		$count = 1;
		if ($type == "checkbox" or $type == "radio") { ?>
			<div class="form-element <?php echo $type ?> <?php echo $class; ?>">
				<span><?php echo $label; ?></span>
				<div class="form-element-wrap">
				<?php foreach ($values as $value): ?>
					<?php 
						$count++; 
						if (!$valueName = $value[$attribute_name]) $valueName = $value["name"]; 
					?>
					<div class="element-wrap">
						<input id="<?php echo $value['slug'].'-'.$count; ?>" <?php if ($inputClass) { echo "class='".$inputClass."'"; } ?> value="<?php echo $value['slug']; ?>" <?php if ($inlineCss) { echo "style='".$inlineCss."'"; } ?> <?php if ($required) { echo "required='required'"; } ?> <?php if ($name) { echo "name='".$name."[]'"; } ?> type="<?php echo $type; ?>">
						<label for="<?php echo $value['slug'].'-'.$count; ?>" ><?php echo $valueName; ?></label>
					</div>
				<?php endforeach ?>
				<?php if ($more): ?>
					<div class="add-more-checkbox-wrap hidden">
						<input type="checkbox" checked="true" ><label></label>
						<input autocomplete="off" type="text" <?php if ($name) { echo "name='".$name."[]'"; } ?> class="add-more-checkbox-input" >
						<div title="Remove" class="remove-checkbox-input">X</div>
					</div>
					<a href="?more" class="btn add-more-checkbox-btn">+ <?php echo Translate::string("form.add_more_btn"); ?></a>
				<?php endif ?>
				</div>
			</div>

			<?php
		} else {
			echo "<div class='form-element $class'>";
			if ($label) { ?><label <?php if ($labelID) { echo "id='".$labelClass."'"; } ?> <?php if ($id) { echo "for='".$id."'"; } ?> <?php if ($labelClass) { echo "class='".$labelClass."'"; } ?> ><?php echo $label; ?></label> <?php } 
			if ($type == "number") { ?>
				<div class="number-input-wrap">
			<?php } ?>
				<input <?php if ($id) { echo "id='".$id."'"; } ?> <?php if ($inputClass) { echo "class='".$inputClass."'"; } ?> <?php if (!$autocomplete) { echo "autocomplete='off'"; } ?> <?php if ($placeholder) { echo "placeholder='".$placeholder."'"; } ?> <?php if ($value) { echo "value='".$value."'"; } ?> <?php if ($inlineCss) { echo "style='".$inlineCss."'"; } ?> <?php if ($required) { echo "required='required'"; } ?> <?php if ($name) { echo "name='".$name."'"; } ?> type="<?php echo ($type == "number") ? 'text' : $type ; ?>">
			<?php if ($type == "number") { ?>
					<div class="number-input-controls">
						<button type="button" class="number-input-up"><img src="lib/images/elements/triangle_up.svg"></button>
						<button type="button" class="number-input-down"><img src="lib/images/elements/triangle_down.svg"></button>
					</div>
				</div>
			<?php }
			echo '</div>';
		}
	}

	public function dropdown(  $settings = array(), $list = array() )
	{
		// Setting the defaults
		if ( !empty($settings["id"]) ) { $id = $settings["id"]; } else { $id = ""; }
		if ( !empty($settings["class"]) ) { $class = $settings["class"]; } else { $class = ""; }

		if ( !empty($settings["dropdownClass"]) ) { $dropdownClass = $settings["dropdownClass"]; } else { $dropdownClass = ""; }

		if ( !empty($settings["btnClass"]) ) { $btnClass = $settings["btnClass"]; } else { $btnClass = ""; }
		if ( !empty($settings["liClass"]) ) { $liClass = $settings["liClass"]; } else { $liClass = ""; }
		if ( !empty($settings["btn"]) ) { $btn = $settings["btn"]; } else { $btn = ""; }

		if ( !empty($settings["label"]) ) { $label = $settings["label"]; } else { $label = ""; }
		if ( !empty($settings["labelID"]) ) { $labelID = $settings["labelID"]; } else { $labelID = ""; }
		if ( !empty($settings["labelClass"]) ) { $labelClass = $settings["labelClass"]; } else { $labelClass = ""; }
		
		echo "<div class='form-element $class'>";
		if ($label) { ?><label <?php if ($labelID) { echo "id='".$labelClass."'"; } ?> <?php if ($id) { echo "for='".$id."'"; } ?> <?php if ($labelClass) { echo "class='".$labelClass."'"; } ?> ><?php echo $label; ?></label> <?php } ?>
		<div class="dropdown <?php echo $dropdownClass ?>">
			<label for="<?php echo $id; ?>" class="dropdown-btn <?php echo $btnClass; ?>"><?php echo $btn; ?></label><input type="checkbox" class="dropdown-checkbox" id="<?php echo $id; ?>">
			<ul>
			<?php foreach ($list as $li): ?>
				<li <?php if ($liClass) { echo "class='".$liClass."'"; } ?>><?php echo $li; ?></li>					
			<?php endforeach ?>
			</ul>
		</div>
		<?php
		echo '</div>';

	}

	public function getCurrencies()
	{
		$database = new Database();
		$database->query('SELECT id, currency FROM currencies ORDER BY currency DESC');		
		$results = $database->fetchAll();
		return $results;
	}

	public function getPaymentMethods()
	{
		$database = new Database();
		$database->query('SELECT id, method FROM payment_methods ORDER BY id ASC');		
		$results = $database->fetchAll();
		return $results;
	}

	public function getLanguages()
	{
		$database = new Database();
		$database->query('SELECT id, native_name, eng_name FROM languages ORDER BY eng_name DESC');		
		$results = $database->fetchAll();
		return $results;
	}


}

?>