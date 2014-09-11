<?php 

class DocElement { 

	// Methods
	public function modal( $id, $title, $content, $footer )
	{
		?>
		<section id="<?php echo $id; ?>" class="modal <?php echo (isset($_GET[$id])) ? "" : "hidden" ; ?>">
			<div class="wrap">
				<header>
					<h2><?php echo $title; ?></h2>
					<button class="close-modal-btn">X</button>
				</header>
				<div class="modal-content"><?php echo $content; ?></div>
				<footer><?php echo $footer; ?></footer>
			</div>
		</section>
		<?php
	}


}

?>