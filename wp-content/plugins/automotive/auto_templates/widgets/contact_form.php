<?php
//********************************************
//	Automotive Contact Form Widget
//***********************************************************

echo $before_widget;
echo $before_title . $title . $after_title; ?>
	<form method="post" action="" class="form_contact">
		<div class="contact_result"></div>

		<input type="text" value="" name="name" placeholder="<?php echo $name; ?>">
		<input type="text" value="" name="email" placeholder="<?php echo $email; ?>">

		<textarea name="message" placeholder="<?php echo $message; ?>"></textarea>
		<input type="submit" value="<?php echo $button; ?>" class="md-button submit_contact_form">
	</form>
<?php
echo $after_widget;